<?php

namespace Itnaida\Core;

use stdClass;

class ArgvInput
{
    public array $param = [];
    private string $command;
    private string $first;
    public string $second;
    public string $third;
    public string $string;
    public array $array;
    public stdClass $options;

    public function __construct(array $argv)
    {
        array_shift($argv); // remove app name
        
        $this->command = $argv[0];

        array_shift($argv); // remove command name

        $this->array = $argv;
        $this->options = $this->getOptions($argv);
        $this->first = isset($argv[0]) ? $argv['0'] : null;
        $this->second = isset($argv[1]) ? $argv['1'] : null;
        $this->third = isset($argv[2]) ? $argv['2'] : null;

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