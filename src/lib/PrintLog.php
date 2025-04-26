<?php

namespace Itnaida\Utils;


abstract class PrintLog
{
    private const RESET = "\033[0m";
    private const GREEN = "\033[32m";
    private const RED = "\033[31m";
    private const YELLOW = "\033[33m";
    private const BLUE = "\033[34m";
    private const CYAN = "\033[36m";

    final public static function info($message) 
    {
        echo self::BLUE .  $message . self::RESET . "\n";
    }

    final public static function success($message)
    {
        echo self::GREEN . $message . self::RESET . "\n";
    }

    final public static function error($message)
    {
        echo self::RED . $message . self::RESET . "\n";
    }

    final public static function warning($message)
    {
        echo self::YELLOW . $message . self::RESET . "\n";
    }
}
