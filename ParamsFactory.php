<?php
namespace FpDbTest;

class ParamsFactory{
    public function getBySpecifier(string $strSpec ): ParameterInterface{
        switch(trim($strSpec)){
            case '?': return new SimpleParameter();
            case '?f': return new FloatParameter();
            case '?d': return new IntegerParameter();
            case '?a': return new ArrayParameter();
            case '?#': return new IdParameter();
            default:
            throw new \Exception("Unknown specifier [$strSpec]");
        }
    }
}