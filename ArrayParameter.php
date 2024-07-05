<?php
namespace FpDbTest;

use FpDbTest\ParameterInterface;

class ArrayParameter implements ParameterInterface
{
    public function replace($arg): string
    {
        if (gettype($arg) != 'array') {
            throw new \Exception("Not array type value [$arg] passed for array parameter [?a]");
        }
        $simpleParam = new SimpleParameter();
        if (array_is_list($arg)) {
            return join(', ', array_map(fn($v): string => $simpleParam->replace($v), $arg));
        } else {
            return join(
                ', ',
                array_map(
                    fn($k,  $v): string => "`$k` = " . (new SimpleParameter())->replace($v),
                    array_keys($arg),
                    array_values($arg)
                )
            );

        }
    }
}
