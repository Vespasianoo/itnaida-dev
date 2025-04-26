<?php

namespace Itnaida\Core;

class CommandManager 
{
    private static array $commands = [];

    public static function addCommand(string $name, string $class): void
    {
        self::$commands[$name] = $class;
    }

    public static function getInstanceClassByCommand($command): Command
    {
        return new self::$commands[$command];
    }

    public static function getCommands(): array
    {
        return self::$commands;
    }

    public static function commandExists(string $command)
    {
        print_r(self::$commands[$command]) . PHP_EOL;
        return class_exists(self::$commands[$command]);
    }
}
