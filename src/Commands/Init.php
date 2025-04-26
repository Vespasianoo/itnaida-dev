<?php

namespace Itnaida\Commands;

use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Utils\PrintLog;

class Init extends Command
{
    public function handle(ArgvInput $argvInput): void
    {
        $currentDir = getcwd();
        $sourcePath = __DIR__ . '/itnaida';
        $destinationPath = $currentDir . '/itnaida';

        if (copy($sourcePath, $destinationPath)) {
            PrintLog::success("Arquivo movido com sucesso para: $destinationPath");
            return;
        } 
        
        PrintLog::error("Erro ao mover o arquivo.");
    }
}
