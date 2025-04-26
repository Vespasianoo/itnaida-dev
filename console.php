<?php

use Itnaida\Commands\Help;
use Itnaida\Commands\MakeModel;
use Itnaida\Core\CommandManager;

CommandManager::addCommand('help', Help::class);
CommandManager::addCommand('make:model', MakeModel::class);