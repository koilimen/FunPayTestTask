<?php
use FpDbTest\Database;

spl_autoload_register(function ($class) {
    $a = array_slice(explode('\\', $class), 1);
    if (!$a) {
        throw new Exception();
    }
    $filename = implode('/', [__DIR__, ...$a]) . '.php';
    require_once $filename;
});

$db = new Database();

$testData = [
    ['SELECT `name`, `surname` FROM foo.bar', ['SELECT ?# FROM foo.bar', [['name','surname']]]],
    ['SELECT `name` FROM foo.bar', ['SELECT ?# FROM foo.bar', ['name']]],
    ['SELECT * FROM foo.bar WHERE foo in (1,2,3)', ['SELECT * FROM foo.bar WHERE foo in (?a)', [[1,2,3]]]],
    ['SELECT * FROM foo.bar WHERE foo in (1,2,NULL)', ['SELECT * FROM foo.bar WHERE foo in (?a)', [[1,2,null]]]],
    ['UPDATE foo.bar SET `name` = \'Jack\', `profession` = \'samurai\'', ['UPDATE foo.bar SET ?a', [['name' => 'Jack', 'profession' => 'samurai']]]],
    ['UPDATE foo.bar SET `name` = \'Jack\', `profession` = NULL', ['UPDATE foo.bar SET ?a', [['name' => 'Jack', 'profession' => null]]]],
    ['SELECT * FROM foo.bar WHERE foo = 1.324', ['SELECT * FROM foo.bar WHERE foo = ?f', ['1.324']]],
    ['SELECT * FROM foo.bar WHERE foo = 1.0', ['SELECT * FROM foo.bar WHERE foo = ?f', [1]]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?f', ['1.0']]],
    ['SELECT * FROM foo.bar WHERE foo = 1.324', ['SELECT * FROM foo.bar WHERE foo = ?f', ['1.324']]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?d', [1.0]]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?d', ['1.2342353']]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?d', [1.2342353]]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?d', [true]]],
    ['SELECT * FROM foo.bar WHERE foo = NULL', ['SELECT * FROM foo.bar WHERE foo = ?d', [NULL]]],
    ['SELECT * FROM foo.bar WHERE foo = 1', ['SELECT * FROM foo.bar WHERE foo = ?d', [1]]],
    ['SELECT * FROM foo.bar', ['SELECT * FROM foo.bar', []]],
    ['SELECT * FROM foo.bar WHERE foo = NULL', ['SELECT * FROM foo.bar WHERE foo = ?', [null]]],
    ['SELECT * FROM foo.bar WHERE foo = 0 AND bar = 1', ['SELECT * FROM foo.bar WHERE foo = ? AND bar = ?', [false, true]]],
    ['SELECT * FROM foo.bar WHERE id = 5', ['SELECT * FROM foo.bar WHERE id = ?', [5]]],
    ['SELECT * FROM foo.bar WHERE id = 5 AND name = \'Alex\'', ['SELECT * FROM foo.bar WHERE id = ? AND name = ?', [5, 'Alex']]],
    ['SELECT * FROM foo.bar WHERE id = 5.5 AND name = \'Alex\'', ['SELECT * FROM foo.bar WHERE id = ? AND name = ?', [5.5, 'Alex']]],
    ['SELECT * FROM foo.bar WHERE id = 5.5 AND name = \'Alex\'', ['SELECT * FROM foo.bar WHERE id = ? {AND name = ?}', [5.5, 'Alex']]],
    ['SELECT * FROM foo.bar WHERE id = 5.5', ['SELECT * FROM foo.bar WHERE id = ? {AND surname = ? AND name = ?}', [5.5, 'Bill', $db->skip()]]],
    ['SELECT * FROM foo.bar WHERE id = 5.5', ['SELECT * FROM foo.bar WHERE id = ? {AND name = ?}', [5.5, $db->skip()]]],
];

foreach ($testData as $testDatum) {
    $expected = $testDatum[0];
    $actual = $db->buildQuery($testDatum[1][0], $testDatum[1][1]);
    if (strcmp($expected, $actual) !== 0) {
        throw new Error("Expected = '$expected', Actual = '$actual'");
    }
}



