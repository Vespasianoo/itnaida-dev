<?php

namespace Itnaida\Core;

class Installer
{
    public static function postInstall()
    {
        $vendorDir = dirname(__DIR__, 2); 
        $projectRoot = self::findProjectRoot($vendorDir);
        
        $source = $vendorDir . '/bin/itnaida';
        $destination = $projectRoot . '/itnaida';
        
        if (!file_exists($destination)) {
            copy($source, $destination);
            chmod($destination, 0755); 
            echo "Arquivo 'itnaida' copiado para a raiz do projeto!\n";
        } else {
            echo "Arquivo 'itnaida' já existe na raiz do projeto.\n";
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
}
