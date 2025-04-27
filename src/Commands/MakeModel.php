<?php

namespace Itnaida\Commands;

use Adianti\Database\TTransaction;
use Exception;
use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Lib\PrintLog;

class MakeModel extends Command
{
    public function handle(ArgvInput $argvInput): void
    {
        try {
            
            $className = $argvInput->getFirstArg();
            $tableName = $this->getTableName($className);

            // Caminho onde o arquivo será criado -> pegar dos parametros
            $modelPath = "./app/model/{$className}.php";

            $modelContent = "<?php\n\n";
            $modelContent .= "use Adianti\Database\TRecord;\n\n";
            $modelContent .= "class {$className} extends TRecord\n";
            $modelContent .= "{\n";
            $modelContent .= "    const TABLENAME = '$tableName';\n";
            $modelContent .= "    const PRIMARYKEY = 'id';\n";
            $modelContent .= "    const IDPOLICY = 'max';\n";
            $modelContent .= "\n";
            $modelContent .= "    public function __construct(\$id = NULL, \$callObjectLoad = TRUE)\n";
            $modelContent .= "    {\n";
            $modelContent .= $this->createAttribute($tableName);
            $modelContent .= "        // Constructor logic here\n";
            $modelContent .= "    }\n";
            $modelContent .= "}\n";

            file_put_contents($modelPath, $modelContent);
            PrintLog::success('ok');
        } catch (Exception $e) {
            PrintLog::error($e->getMessage());
        }
    }

    private function getAttributeTable(string $tableName): array
    {
        TTransaction::open('permission');
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

    private function createAttribute($tableName): string
    {
        $modelContent = "        parent::__construct(\$id, \$callObjectLoad);\n";
        $attrs = $this->getAttributeTable($tableName);

        if ($attrs) {
            foreach ($attrs as $attribute_name) {
                $modelContent .= "        parent::addAttribute('$attribute_name');\n";
            }
        }

        return $modelContent;
    }

    private function getTableName($className)
    {
        // Adiciona um underscore antes de cada letra maiúscula (exceto a primeira)
        $textoSeparado = preg_replace_callback('/([a-z])([A-Z])/', function($matches) {
            return $matches[1] . '_' . strtolower($matches[2]);
        }, $className);

        return strtolower($textoSeparado);
    }
}
