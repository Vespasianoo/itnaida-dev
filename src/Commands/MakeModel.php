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
    private string $modelPath = './app/model/';

    public function handle(ArgvInput $argvInput): void
    {
        try {
            
            $this->className = $argvInput->getFirstArg();
            $this->tableName = $this->getTableName($this->className);
            $this->connector = $argvInput->getSecondArg();

            if (empty($this->connector) && $this->connector == '')
            {
                throw new Exception("Conector não informado.");
            }

            $this->setModelPath($this->className . '.php');


            $this->build();

            PrintLog::success("Model {$this->className} criada com sucesso");
        } catch (Exception $e) {
            PrintLog::error($e->getMessage());
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
        
        file_put_contents($this->modelPath, $modelContent);
    }

    private function setModelPath($path = null) {
        $this->modelPath .= "{$path}";
    }

    private function getAttributeTable(string $tableName): array
    {
        TTransaction::open($this->connector);
        $conn = TTransaction::get();
        $conn = $conn->query("PRAGMA table_info($tableName)");

        $columns_tables = $conn->fetchAll();

        if (empty($columns_tables)) {
            throw new Exception("Verifique se a tabela $tableName existe no banco de dados.");
        }

        $columns = [];
        foreach ($columns_tables as $column) {
            if ($column['name'] != 'id')
            {
                $columns[] = $column['name'];
            }
        }

        return $columns;
    }

    private function generateAttributes($tableName): string
    {
        $attrs = $this->getAttributeTable($tableName);
        $content = '';
    
        foreach ($attrs as $attr) {
            $content .= "        parent::addAttribute('$attr');\n";
        }
    
        return $content;
    }
    
    private function getTableName($className)
    {
        // Adiciona um underscore antes de cada letra maiúscula (exceto a primeira)
        $textoSeparado = preg_replace_callback('/([a-z])([A-Z])/', function($matches) {
            return $matches[1] . '_' . strtolower($matches[2]);
        }, $className);

        return strtolower($textoSeparado);
    }

    private function getStubs() {
        return file_get_contents('./vendor/vespasiano/itnaida/src/templates/model.stub');
    }
}
