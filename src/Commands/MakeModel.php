<?php

namespace Itnaida\Commands;

use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;

class MakeModel extends Command
{
    public function handle(ArgvInput $argvInput): void
    {
        print_r($argvInput);
    }
}
