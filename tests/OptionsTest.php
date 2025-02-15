<?php

declare(strict_types=1);

use Gorle\Wc\CountMode;
use Gorle\Wc\InputMode;
use Gorle\Wc\Options;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    #[DataProvider('countModesProvider')]
    public function test_count_modes_are_added_individually(array $countModes)
    {
        $options = new Options;

        array_walk($countModes, fn ($mode) => $options->addCountMode($mode));
        $optionsCountModes = $options->countModes();

        array_walk($countModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        array_walk($countModes, fn ($mode) => $this->assertTrue(array_search($mode, $optionsCountModes) !== false));
    }

    #[DataProvider('countModesProvider')]
    public function test_count_modes_are_added_in_bulk_by_named_constructor(array $countModes)
    {
        $options = Options::withCountModes($countModes);

        $optionsCountModes = $options->countModes();

        array_walk($countModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        array_walk($countModes, fn ($mode) => $this->assertTrue(array_search($mode, $optionsCountModes) !== false));
    }

    #[DataProvider('countModesProvider')]
    public function test_count_modes_are_added_in_bulk_by_setter(array $countModes)
    {
        $options = new Options;
        $options->addCountModes($countModes);

        $optionsCountModes = $options->countModes();

        array_walk($countModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        array_walk($countModes, fn ($mode) => $this->assertTrue(array_search($mode, $optionsCountModes) !== false));
    }

    #[DataProvider('duplicateCountModesProvider')]
    public function test_count_modes_are_only_added_once(array $countModes, array $expectedCountModes)
    {
        $options = new Options;
        $options->addCountModes($countModes);

        $optionsCountModes = $options->countModes();

        array_walk($expectedCountModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        $this->assertCount(count($expectedCountModes), $optionsCountModes);
    }

    #[DataProvider('noisyCountModesProvider')]
    public function test_only_valid_count_modes_are_added(array $countModes, array $expectedCountModes)
    {
        $options = new Options;
        $options->addCountModes($countModes);

        $optionsCountModes = $options->countModes();

        array_walk($expectedCountModes, fn ($mode) => $this->assertTrue($options->hasCountMode($mode)));
        array_walk($expectedCountModes, fn ($mode) => $this->assertTrue(array_search($mode, $optionsCountModes) !== false));
        $this->assertCount(count($expectedCountModes), $optionsCountModes);
    }

    #[DataProvider('filenamesProvider')]
    public function test_files_are_added(array $filenames)
    {
        $options = new Options;

        array_walk($filenames, fn ($file) => $options->addFilename($file));

        $optionsFilenames = $options->filenames();

        array_walk($filenames, fn ($file) => $this->assertTrue(array_search($file, $optionsFilenames) !== false));
    }

    #[DataProvider('filenamesAndInputModesProvider')]
    public function test_input_mode_is_determined(array $filenames, InputMode $expectedInputMode)
    {
        $options = new Options;

        array_walk($filenames, fn ($file) => $options->addFilename($file));

        $this->assertEquals($expectedInputMode, $options->inputMode());
    }

    public static function countModesProvider(): iterable
    {
        yield '1.1: Character mode' => [
            [CountMode::CHARACTER],
        ];

        yield '1.2: Multibyte Character mode' => [
            [CountMode::MB_CHARACTER],
        ];

        yield '1.3: Line mode' => [
            [CountMode::LINE],
        ];

        yield '1.4: Word mode' => [
            [CountMode::WORD],
        ];

        yield '2.1: Character & Multibyte Character modes' => [
            [CountMode::CHARACTER, CountMode::MB_CHARACTER],
        ];
    }

    public static function duplicateCountModesProvider(): iterable
    {
        yield '1.1: Character mode' => [
            [CountMode::CHARACTER, CountMode::CHARACTER],
            [CountMode::CHARACTER],
        ];

        yield '1.2: Multibyte Character mode' => [
            [CountMode::MB_CHARACTER, CountMode::MB_CHARACTER],
            [CountMode::MB_CHARACTER],
        ];

        yield '1.3: Line mode' => [
            [CountMode::LINE, CountMode::LINE],
            [CountMode::LINE],
        ];

        yield '1.4: Word mode' => [
            [CountMode::WORD, CountMode::WORD],
            [CountMode::WORD],
        ];

        yield '2.1: Character & Multibyte Character modes' => [
            [CountMode::CHARACTER, CountMode::MB_CHARACTER, CountMode::CHARACTER, CountMode::MB_CHARACTER],
            [CountMode::CHARACTER, CountMode::MB_CHARACTER],
        ];
    }

    public static function noisyCountModesProvider(): iterable
    {
        yield '1.1: Character mode' => [
            [CountMode::CHARACTER, 'junk'],
            [CountMode::CHARACTER],
        ];

        yield '2.1: Character & Multibyte Character modes' => [
            [CountMode::CHARACTER, 'junk', CountMode::MB_CHARACTER, 'more junk'],
            [CountMode::CHARACTER, CountMode::MB_CHARACTER],
        ];
    }

    public static function filenamesProvider(): iterable
    {
        yield '1: Single filename' => [['my_file']];
        yield '2: Multiple filenames' => [['my_file', 'my_second_file']];
    }

    public static function filenamesAndInputModesProvider(): iterable
    {
        yield '1: FILE Mode' => [
            ['my_file'],
            InputMode::FILE,
        ];
        yield '2: STDIN Mode' => [
            [],
            InputMode::STDIN,
        ];
    }
}
