<?php

namespace Itnaida\Core;


class ArgvInput
{
    public array $param = [];
    private string $command;
    private ?string $first = null;
    private ?string $second = null;
    private ?string $third = null;
    public string $string;
    public array $array;
    public array $options;

    public function __construct(?array $argv = null)
    {
        $argv ??= $_SERVER['argv'] ?? [];

        $this->param = $argv;
        array_shift($argv); // remove app name
        
        $this->command = array_shift($argv) ?? 'help';
        
        $this->array = $argv;
        $this->options = $this->getOptions($argv);
      
        $argv = $this->removeOptionInArgv($argv, $this->options);
        
        [$this->first, $this->second, $this->third] = array_pad($argv, 3, '');
      
        $this->string = $this->getStringCommand();
    } 

    private function removeOptionInArgv(array $argv)
    {
        $new = [];

        foreach ($argv as $item) {
            if ($item[0] != '-') {
                $new[] = $item;
            }   
        }

        return $new;
    }
    
    private function getStringCommand()
    {
        return trim("{$this->first} {$this->second} {$this->third}");
    }

    public function getCommand()
    {
        return $this->command;
    }
    
    function getOptions(array $itens): array
    {
        $options = [];
    
        if (empty($itens)) return $options;
    
        foreach ($itens as $item) {
            if (isset($item[0]) && $item[0] === '-' && strlen($item) > 1) {
                $caracteres = str_split(substr($item, 1));
                foreach ($caracteres as $char) {
                    $options[$char] = true;
                }
            }
        }

        return $options;
    }
    

    public function getFirstArg()
    {
        return $this->first;
    }

    public function getSecondArg()
    {
        return $this->second;
    }

    public function getThirdArg()
    {
        return $this->third;
    }
}
