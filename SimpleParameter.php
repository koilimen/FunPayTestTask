<?php
namespace FpDbTest;

class SimpleParameter implements ParameterInterface{
    public function replace($arg): string{
        $replacer = null;
        switch (gettype($arg)) {
            case 'string':
                $replacer = "'$arg'";
                break;
            case 'boolean':
                $replacer = $arg ? '1' : "0";
                break;
            case 'NULL':
                $replacer = 'NULL';
                break;
            case 'integer':
            case 'double':
                $replacer = $arg;
                break;
            default:
                throw new \Exception("unknoen type of argument");
        }
        return $replacer;
    }

}