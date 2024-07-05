<?php

namespace FpDbTest;

use Exception;
use mysqli;

class Database implements DatabaseInterface
{
    private mysqli $mysqli;
    private ParamsFactory $paramsFactory;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->paramsFactory = new ParamsFactory();
    }

    private function buildCondition(string $query, array $args = []): string
    {
        if (in_array($this->skip(), $args)) {
            return '';
        }
        return $this->buildQuery($query, $args);
    }

    public function buildQuery(string $query, array $args = []): string
    {
        $b = '';
        $conditionBufer = '';
        $argsCounterInConditionalBlock = 0;
        $counter = 0;
        $queryLen = strlen($query);
        $conditionStart = -1;
        for ($i = 0; $i < $queryLen; $i++) {
            if ($conditionStart > -1) {
                if ($query[$i] == '}') {
                    $conditionStart = -1;
                    $b .= $this->buildCondition($conditionBufer, array_slice($args, $counter, $argsCounterInConditionalBlock));
                    $counter += $argsCounterInConditionalBlock;
                } else {
                    $conditionBufer .= $query[$i];
                    if ($query[$i] == '?') {
                        $argsCounterInConditionalBlock++;
                    }
                }
                continue;
            }
            if ($query[$i] == '?') {
                if ($i < ($queryLen - 1) && str_contains('dfa#', $query[$i + 1])) {
                    $specifier = $query[$i + 1];
                    $param = $this->paramsFactory->getBySpecifier("?$specifier");
                    $b .= $param->replace($args[$counter]);
                    $i++;
                } else {
                    $parameter = $this->paramsFactory->getBySpecifier($query[$i]);
                    $b .= $parameter->replace($args[$counter]);
                }
                $counter++;
            } elseif ($query[$i] == '{') {
                $conditionStart = $i;
            } else {
                $b .= $query[$i];
            }
        }
        return trim($b);
    }

    public function skip()
    {
        return "__SKIP_CONDITION__";
    }
}
