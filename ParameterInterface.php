<?php
namespace FpDbTest;

interface ParameterInterface
{
    public function replace($arg): string;
}