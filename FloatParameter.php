<?php
namespace FpDbTest;

class FloatParameter implements ParameterInterface{
    public function replace($arg): string{
        $replacer = null;
        switch (gettype($arg)) {
            case 'string':
                $replacer = floatval($arg);
                break;
            case 'NULL':
                $replacer = 'NULL';
                break;
            case 'integer':
                $replacer = sprintf('%.1f', $arg);
                break;
            default:
                throw new Exception('Unknown type for ?f - ' . gettype($arg));
        }
        return $replacer;
    }

}