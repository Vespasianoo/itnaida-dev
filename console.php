<?php

use Itnaida\Commands\Help;
use Itnaida\Commands\Init;
use Itnaida\Commands\MakeController;
use Itnaida\Commands\MakeModel;
use Itnaida\Core\CommandManager;

CommandManager::addCommand('help', Help::class);
CommandManager::addCommand('make:model', MakeModel::class);
CommandManager::addCommand('make:controller', MakeController::class);
CommandManager::addCommand('init', Init::class);
