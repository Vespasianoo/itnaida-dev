<?php

namespace Itnaida\Core;

abstract class Command
{
    abstract public function handle(ArgvInput $argvInput): void;
}
