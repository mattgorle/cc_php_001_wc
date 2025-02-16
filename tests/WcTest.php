<?php

declare(strict_types=1);

use Gorle\Wc\Wc;
use PHPUnit\Framework\TestCase;

class WcTest extends TestCase {
    public function test_wc_works_end_to_end()
    {
        $GLOBALS['argv'] = [
            '',
            __DIR__ . '/art_of_war.txt'
        ];

        ob_start();

        Wc::main();

        $output = ob_get_flush();

        $this->assertEquals('  7145  58164 342190 ' . __DIR__  . '/art_of_war.txt' . PHP_EOL, $output);
    }
}
