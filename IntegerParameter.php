<?php
namespace FpDbTest;

class IntegerParameter implements ParameterInterface{
    public function replace($arg): string
    {
        $replacer = null;
        switch (gettype($arg)) {
            case 'string':
            case 'double':
                $replacer = intval($arg);
                break;
            case 'boolean':
                $replacer = $arg ? '1' : "0";
                break;
            case 'NULL':
                $replacer = 'NULL';
                break;
            case 'integer':
                $replacer = $arg;
                break;
            default:
                throw new Exception('Unknown type for ?d - ' . gettype($arg));
        }
        return $replacer;
    }

}