<?php

use Itnaida\Commands\MakeModel;
use Itnaida\Core\CommandManager;

CommandManager::addCommand('make:model', MakeModel::class);