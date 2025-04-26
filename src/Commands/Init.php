<?php

namespace Itnaida\Commands;

use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Lib\PrintLog;

class Init extends Command
{
    public function handle(ArgvInput $argvInput): void
    {
        $currentDir = getcwd();
        $sourcePath = $currentDir .'./vendor/vespasiano/itnaida/bin/itnaida';
        $destinationPath = $currentDir . '/itnaida';

        if (copy($sourcePath, $destinationPath)) {
            PrintLog::success("Success");
            return;
        } 
        
        PrintLog::error("Erro ao mover o arquivo.");
    }
}
