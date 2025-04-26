<?php

namespace Itnaida\Core;

use stdClass;

class ArgvInput
{
    public array $param = [];
    private string $command;
    private ?string $first = null;
    private ?string $second = null;
    private ?string $third = null;
    public string $string;
    public array $array;
    public stdClass $options;

    public function __construct(array $argv)
    {
        array_shift($argv); // remove app name
        
        $this->command = array_shift($argv) ?? 'help';
        
        $this->array = $argv;
        $this->options = $this->getOptions($argv);
        [$this->first, $this->second, $this->third] = array_pad($argv, 3, '');

        $this->string = $this->getStringCommand();
    } 
    
    private function getStringCommand()
    {
        $string = '';
        $string .= $this->first ? $this->first . ' ' :  '';
        $string .= $this->second ? $this->second . ' ': '';
        $string .= $this->third ?  $this->third . ' ' : '';
        return trim($string);
    }

    public function getCommand()
    {
        return $this->command;
    }
    

    private function getOptions($argv)
    {
        return new stdClass;
      // definir na class do comando qual os options esperados para tal comando (flags e a versao extend)
      // ex -f --face
      // definir tbm a descrição 
    }

    public function get_first()
    {
        return $this->first;
    }

}