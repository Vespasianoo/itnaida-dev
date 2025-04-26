<?php

namespace Itnaida\Commands;

use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Core\CommandManager;

class Help extends Command
{
    public function handle(ArgvInput $argvInput): void
    {
        $commands = CommandManager::getCommands();

        echo 'itnaida [COMMAND] ' . PHP_EOL;
        echo PHP_EOL;

        foreach ($commands as $comandName => $comand)
        {
            echo $comandName .  PHP_EOL;
        }
    }
}
