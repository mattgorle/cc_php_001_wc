<?php

declare(strict_types=1);

use Gorle\Wc\Wc;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WcTest extends TestCase {
    #[DataProvider('wcArgsProvider')]
    public function test_wc_works_end_to_end($argv, $expectedOutput)
    {
        $GLOBALS['argv'] = $argv;

        ob_start();

        Wc::main();

        $output = ob_get_clean();

        $this->assertEquals($expectedOutput, $output);
    }

    public static function wcArgsProvider(): iterable
    {
        yield '1: default mode' => [
            ['', __DIR__ . '/art_of_war.txt'],
            '  7145  58164 342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '2.1: single mode - character mode' => [
            ['', '-c', __DIR__ . '/art_of_war.txt'],
            '342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '2.2: single mode - multibyte character mode' => [
            ['', '-m', __DIR__ . '/art_of_war.txt'],
            '339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '2.3: single mode - line mode' => [
            ['', '-l', __DIR__ . '/art_of_war.txt'],
            '7145 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '2.4: single mode - word mode' => [
            ['', '-w', __DIR__ . '/art_of_war.txt'],
            '58164 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.1: multiple modes - character and multibyte character mode' => [
            ['', '-c', '-m', __DIR__ . '/art_of_war.txt'],
            '342190 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.2: multiple modes - character and line mode' => [
            ['', '-c', '-l', __DIR__ . '/art_of_war.txt'],
            '  7145 342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.3: multiple modes - character and word mode' => [
            ['', '-c', '-w', __DIR__ . '/art_of_war.txt'],
            ' 58164 342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.4: multiple modes - multibyte character and line mode' => [
            ['', '-m', '-l', __DIR__ . '/art_of_war.txt'],
            '  7145 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.5: multiple modes - multibyte character and word mode' => [
            ['', '-m', '-w', __DIR__ . '/art_of_war.txt'],
            ' 58164 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.6: multiple modes - line and word mode' => [
            ['', '-l', '-w', __DIR__ . '/art_of_war.txt'],
            ' 7145 58164 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.7: multiple modes - character, line and word mode' => [
            ['', '-l', '-w', '-c', __DIR__ . '/art_of_war.txt'],
            '  7145  58164 342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.8: multiple modes - character, multibyte character and line mode' => [
            ['', '-l', '-m', '-c', __DIR__ . '/art_of_war.txt'],
            '  7145 342190 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.9: multiple modes - character, multibyte character and word mode' => [
            ['', '-w', '-m', '-c', __DIR__ . '/art_of_war.txt'],
            ' 58164 342190 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.10: multiple modes - multibyte character, line and word mode' => [
            ['', '-l', '-w', '-m', __DIR__ . '/art_of_war.txt'],
            '  7145  58164 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];

        yield '3.11: multiple modes - character, multibyte character, line and word mode' => [
            ['', '-l', '-w', '-m', '-c', __DIR__ . '/art_of_war.txt'],
            '  7145  58164 342190 339292 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL
        ];
    }
}
