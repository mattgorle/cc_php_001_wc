<?php

use Gorle\Wc\Counter;
use Gorle\Wc\CountMode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CounterTest extends TestCase
{
    #[DataProvider('countByModeProvider')]
    public function test_count_by_mode(CountMode $countMode, string $content, int $expectedResult)
    {
        Counter::reset();

        $result = Counter::count($content, $countMode);

        $this->assertEquals($expectedResult, $result);
    }

    #[DataProvider('countByWordsChunkProvider')]
    public function test_count_by_word_handles_chunk_boundaries(array $chunks, int $expectedResult)
    {
        Counter::reset();

        $result = 0;

        foreach ($chunks as $chunk) {
            $result += Counter::count($chunk, CountMode::WORD);
        }

        $this->assertEquals($expectedResult, $result);
    }

    public static function countByModeProvider(): iterable
    {
        yield '1.1: Character mode, ASCII' => [
            CountMode::CHARACTER,
            'foo',
            3,
        ];

        yield '1.2: Character mode, MultiByte' => [
            CountMode::CHARACTER,
            'fóóo',
            6,
        ];

        yield '1.3: Character mode, test document' => [
            CountMode::CHARACTER,
            file_get_contents(__DIR__.'/art_of_war.txt'),
            342_190,
        ];

        yield '2.1: MultiByte Character mode, ASCII' => [
            CountMode::MB_CHARACTER,
            'fooo bar stool',
            14,
        ];

        yield '2.2: MultiByte Character mode, MultiByte' => [
            CountMode::MB_CHARACTER,
            'fóóo bár',
            8,
        ];

        yield '2.3: MultiByte Character mode, test document' => [
            CountMode::MB_CHARACTER,
            file_get_contents(__DIR__.'/art_of_war.txt'),
            339_292,
        ];

        yield '3.1: Line mode, \n' => [
            CountMode::LINE,
            "foo\nbar\nbaz\n",
            3,
        ];

        yield '3.2: Line mode, \r' => [
            CountMode::LINE,
            "foo\rbar\rbaz\r",
            3,
        ];

        yield '3.3: Line mode, \r\n' => [
            CountMode::LINE,
            "foo\r\nbar\r\nbaz\r\n",
            3,
        ];

        yield '3.4: Line mode, test document' => [
            CountMode::LINE,
            file_get_contents(__DIR__.'/art_of_war.txt'),
            7_145,
        ];

        yield '4.1: Word mode, ASCII' => [
            CountMode::WORD,
            'foo bar',
            2,
        ];

        yield '4.2: Word mode, MultiByte' => [
            CountMode::WORD,
            'foó báar báz',
            3,
        ];

        yield '4.3: Word mode, test document' => [
            CountMode::WORD,
            file_get_contents(__DIR__.'/art_of_war.txt'),
            58_164,
        ];
    }

    public static function countByWordsChunkProvider(): iterable
    {
        yield '1: Single chunk' => [
            ['one chunk with five words'],
            5,
        ];

        yield '2.1: Multiple chunks, not overlapping' => [
            [
                'one chunk with five words ',
                'and another with five more',
            ],
            10,
        ];

        yield '2.2: Multiple chunks, overlapping' => [
            [
                'one chunk with five word',
                'sand another with four more',
            ],
            9,
        ];
    }
}
