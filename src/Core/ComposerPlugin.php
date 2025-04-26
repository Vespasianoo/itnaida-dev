<?php

namespace Itnaida\Core;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        // Não precisa fazer nada aqui no seu caso
    }

    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::postAutoloadDump => 'copyBinToRoot'
        ];
    }

    public static function copyBinToRoot(Event $event)
    {
        $vendorDir = dirname(__DIR__, 2); // /vendor/vespasiano/itnaida
        $projectRoot = self::findProjectRoot($vendorDir);

        $source = $vendorDir . '/bin/itnaida';
        $destination = $projectRoot . '/itnaida';

        if (!file_exists($destination)) {
            copy($source, $destination);
            chmod($destination, 0755);
            $event->getIO()->write("<info>[Itnaida]</info> Binário 'itnaida' copiado para a raiz do projeto!");
        } else {
            $event->getIO()->write("<comment>[Itnaida]</comment> Binário 'itnaida' já existe, nada feito.");
        }
    }

    private static function findProjectRoot($startDir)
    {
        $currentDir = $startDir;
        while (!file_exists($currentDir . '/composer.json')) {
            $parentDir = dirname($currentDir);
            if ($parentDir === $currentDir) {
                throw new \Exception('Não foi possível encontrar a raiz do projeto.');
            }
            $currentDir = $parentDir;
        }
        return $currentDir;
    }


    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Deixe vazio se não precisa fazer nada ao desativar
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Deixe vazio se não precisa fazer nada ao desinstalar
    }
}
