<?php

declare(strict_types=1);

namespace Gorle\Wc;

class Wc
{
    const READ_PAGE_SIZE = 16 * 1_024 * 1_024;

    protected static ?Options $options = null;

    public static function main(): int
    {
        static::$options = OptionParser::parseOptions($GLOBALS['argv']);

        $files = static::$options->inputMode() === InputMode::FILE ? static::$options->filenames() : [STDIN];
        $countModes = static::$options->countModes();

        $displayPrinter = new DisplayPrinter;

        foreach ($files as $file) {
            if ($file !== STDIN && is_dir($file)) {
                $displayPrinter->addLine("${file}: Is a directory", LineType::LITERAL);
                $displayPrinter->addLine([...static::zeroCount(), 'title' => $file]);
            }

            if ($file !== STDIN && ! is_file($file)) {
                continue;
            }

            $filename = is_string($file) ? $file : '';
            $count = [...static::countFile($file), 'title' => $filename];

            $displayPrinter->addLine($count, LineType::COUNT);
        }

        echo $displayPrinter->print();

        return 0;
    }

    protected static function zeroCount(): array
    {
        $countModes = static::$options->countModes();
        $counts = static::initialiseModesArray();

        foreach ($countModes as $countMode) {
            $counts[$countMode->name] += 0;
        }

        return $counts;
    }

    protected static function countFile($file): array
    {
        $countModes = static::$options->countModes();
        $counts = static::initialiseModesArray();
        Counter::reset();

        $handle = is_string($file) ? fopen($file, 'r') : $file;

        do {
            $content = fread($handle, static::READ_PAGE_SIZE);

            if ($content === false) {
                continue;
            }

            foreach ($countModes as $countMode) {
                $counts[$countMode->name] += Counter::count($content, $countMode);
            }
        } while ($content !== false && ! feof($handle));

        fclose($handle);

        return $counts;
    }

    protected static function initialiseModesArray(): array
    {
        $countModes = static::$options->countModes();

        $modesArray = [];
        foreach ($countModes as $countMode) {
            $modesArray[$countMode->name] = 0;
        }

        return $modesArray;
    }
}
