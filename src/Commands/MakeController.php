<?php

namespace Itnaida\Commands;

use Exception;
use Itnaida\Core\ArgvInput;
use Itnaida\Core\Command;
use Itnaida\Core\Stubs;
use Itnaida\Lib\PrintLog;

class MakeController extends Command
{
    private string $className;
    private string $controllersPath = './app/control';
    private string $path;

    public function handle(ArgvInput $argvInput): void
    {
        try {
            $this->setClassName($argvInput->getFirstArg());
            $this->setPath($argvInput->getSecondArg());
            
            $this->build();

            PrintLog::success("Controller '{$this->className}' was created successfully.");
        } catch (Exception $e) {
            PrintLog::error("Error: " . $e->getMessage());
        }
    }

    private function build()
    {
        $template = $this->getStub();

        $controllerContent = str_replace(
            '{{className}}', 
            $this->className,
            $template
        );

        $this->createFile($controllerContent);
    }

    private function createFile($controllerContent)
    {
        $path_target = "{$this->controllersPath}";
        
        if ($this->path) {
            $path_target .= "/{$this->path}";
        }
        
        if (!is_dir($path_target)) {
            mkdir($path_target, 0777, true);
        }

        $path_target .= "/{$this->className}.php";

        file_put_contents($path_target, $controllerContent);
    }

    private function setClassName(string $firstArg)
    {
        if (empty($firstArg)) {
            throw new Exception("The controller name is required.");
        }

        $this->className = $firstArg;
        return $this->className;
    }

    private function setPath(?string $secondArg)
    {
        $this->path = $secondArg ?? '';
        return $this->path;
    }

    private function getStub()
    {
        $stub = file_get_contents(Stubs::pathBase . '/controller.stub');

        if (!$stub) {
            throw new Exception("Controller stub not found.");
        }

        return $stub;
    }
}
