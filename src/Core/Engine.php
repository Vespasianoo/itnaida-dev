<?php

namespace Itnaida\Core;

use Itnaida\Core\CommandManager;
use Itnaida\Lib\PrintLog;

class Engine
{
    public static function run(ArgvInput $argvInput)
    {
        $command = $argvInput->getCommand();

        if (!CommandManager::commandExists($command))
        {
            PrintLog::error("Command \"{$command}\" not found.");
            PrintLog::info("Tip: use the \"help\" command to see all available commands");
            return; 
        }
        
        $commandObject = CommandManager::getInstanceClassByCommand($command);
        $commandObject->handle($argvInput);
    }
}
