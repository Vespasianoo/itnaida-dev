<?php

namespace Itnaida\Commands;

use Adianti\Database\TTransaction;
use Exception;
use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Lib\PrintLog;

class MakeModel extends Command
{
    private string $className;
    private string $tableName;
    private string $connector;
    private string $modelPath = './app/model';
    private string $path = '';

    public function handle(ArgvInput $argvInput): void
    {
        try {
            $this->className = $argvInput->getFirstArg();

            if (empty($this->className)) {
                throw new Exception("Model class name is required.");
            }

            $this->tableName = $this->getTableName($this->className);
            $this->connector = $argvInput->getSecondArg();
            $this->path = $argvInput->getThirdArg();

            if (empty($this->connector)) {
                throw new Exception("Database connector must be specified.");
            }

            $this->build();

            PrintLog::success("Model '{$this->className}' was created successfully.");
        } catch (Exception $e) {
            PrintLog::error("Error: " . $e->getMessage());
        }
    }

    private function build()
    {
        $attributes = $this->generateAttributes($this->tableName);
        $template = $this->getStubs();

        $modelContent = str_replace(
            ['{{className}}', '{{tableName}}', '{{attributes}}'],
            [$this->className, $this->tableName, $attributes],
            $template
        );

        $this->createFile($modelContent);
    }

    private function createFile(string $modelContent): void
    {
        $path_target = "{$this->modelPath}";

        if ($this->path) {
            $path_target .= "/{$this->path}";
        }

        if (!is_dir($path_target)) {
            mkdir($path_target, 0777, true);
        }

        $filePath = rtrim($path_target, '/') . "/{$this->className}.php";
        file_put_contents($filePath, $modelContent);
    }

    private function getAttributeTable(string $tableName): array
    {
        TTransaction::open($this->connector);
        $conn = TTransaction::get();
        $result = $conn->query("PRAGMA table_info($tableName)");

        $columns = $result->fetchAll();

        if (empty($columns)) {
            throw new Exception("Table '{$tableName}' was not found in the database. Please check the name and connection.");
        }

        $attributes = [];
        foreach ($columns as $column) {
            if ($column['name'] !== 'id') {
                $attributes[] = $column['name'];
            }
        }

        return $attributes;
    }

    private function generateAttributes(string $tableName): string
    {
        $attributes = $this->getAttributeTable($tableName);
        $content = '';

        foreach ($attributes as $attr) {
            $content .= "        parent::addAttribute('{$attr}');\n";
        }

        return $content;
    }

    private function getTableName(string $className): string
    {
        // Add underscore before each uppercase letter (except the first)
        $separated = preg_replace_callback('/([a-z])([A-Z])/', function ($matches) {
            return $matches[1] . '_' . strtolower($matches[2]);
        }, $className);

        return strtolower($separated);
    }

    private function getStubs(): string
    {
        $stub = file_get_contents('./vendor/vespasiano/itnaida/src/templates/model.stub');

        if (!$stub) {
            throw new Exception("Model stub file not found.");
        }

        return $stub;
    }
}
