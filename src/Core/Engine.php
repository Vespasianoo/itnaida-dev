<?php

namespace Itnaida\Core;

use Itnaida\Core\CommandManager;

class Engine
{
    public static function run(ArgvInput $argvInput)
    {
        $command = $argvInput->getCommand();

        if (!CommandManager::commandExists($command))
        {
            echo PHP_EOL;
            echo "Comando \"{$command}\" não encontrado." . PHP_EOL;
            echo "Dica: use o comando \"help\" para ver todos os comandos disponíveis." . PHP_EOL;
            echo PHP_EOL;
            return;
        }
        
        $commandObject = CommandManager::getInstanceClassByCommand($command);
        $commandObject->handle($argvInput);
    }
}
