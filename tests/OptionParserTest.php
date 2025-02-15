<?php

declare(strict_types=1);

use Gorle\Wc\CountMode;
use Gorle\Wc\InputMode;
use Gorle\Wc\OptionParser;
use Gorle\Wc\Options;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OptionParserTest extends TestCase
{
    #[DataProvider('cliArgumentsProvider')]
    public function test_cli_arguments_are_parsed_into_an_options_object(
        array $parameters,
        array $expectedModes,
        array $expectedFilenames,
        InputMode $expectedInputMode
    ) {
        $options = OptionParser::parseOptions($parameters);

        $this->assertInstanceOf(Options::class, $options);

        array_walk($expectedModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        array_walk($expectedFilenames, fn ($file) => $this->assertTrue(array_search($file, $options->filenames()) !== false));

        $this->assertEquals($expectedInputMode, $options->inputMode());
    }

    public static function cliArgumentsProvider(): iterable
    {
        yield '1.1: Explicit modes, one filename' => [
            ['', '-c', '-l', 'file1'],
            [CountMode::CHARACTER, CountMode::LINE],
            ['file1'],
            InputMode::FILE,
        ];

        yield '1.2: Explicit modes, multiple filenames' => [
            ['', '-c', '-m', 'file1', 'file2'],
            [CountMode::CHARACTER, CountMode::MB_CHARACTER],
            ['file1', 'file2'],
            InputMode::FILE,
        ];

        yield '1.3: Explicit modes, no filenames (STDIN)' => [
            ['', '-c', '-m'],
            [CountMode::CHARACTER, CountMode::MB_CHARACTER],
            [],
            InputMode::STDIN,
        ];

        yield '2.1: Default modes, one filename' => [
            ['', 'file1'],
            [CountMode::CHARACTER, CountMode::WORD, CountMode::LINE],
            ['file1'],
            InputMode::FILE,
        ];

        yield '2.2: Default modes, multiple filenames' => [
            ['', 'file1', 'file2'],
            [CountMode::CHARACTER, CountMode::WORD, CountMode::LINE],
            ['file1', 'file2'],
            InputMode::FILE,
        ];

        yield '2.3: Default modes, no filenames (STDIN)' => [
            [''],
            [CountMode::CHARACTER, CountMode::WORD, CountMode::LINE],
            [],
            InputMode::STDIN,
        ];
    }
}
