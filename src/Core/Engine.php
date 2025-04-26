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
            echo 'Comando não existe';
            return;
        }
        
        $commandObject = CommandManager::getInstanceClassByCommand($command);
        $commandObject->handle($argvInput);
    }
}
