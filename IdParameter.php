<?php
namespace FpDbTest;

class IdParameter implements ParameterInterface{
    public function replace($arg): string
    {
        switch(gettype($arg)){
            case 'string': return "`$arg`";
            case 'array':
                if(array_is_list($arg)){
                    return join(', ', array_map(fn($v): string => "`$v`", $arg));
                }
            default:
            throw new \Exception("Unknown type for ?# parameter - string or array of strings allowed ");
        }
    }
}