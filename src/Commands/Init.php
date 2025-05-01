<?php

namespace Itnaida\Commands;

use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Lib\PrintLog;

class Init extends Command
{
    const DESCRIPTION = 'Initializes the project by creating the "itnaida" file';
 
    public function handle(ArgvInput $argvInput): void
    {
        $currentDir = getcwd();
        $sourcePath = $currentDir . '/vendor/vespasiano/itnaida/bin/itnaida';
        $destinationPath = $currentDir . '/itnaida';

        if (copy($sourcePath, $destinationPath)) {

            if (chmod($destinationPath, 0755)) {
                PrintLog::success("Sucesso");
            } 

            return;
        }

        PrintLog::error("Erro ao mover o arquivo. Você esta na raiz do projeto ?");
    }
}
