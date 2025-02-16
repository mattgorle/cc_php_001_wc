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

        $displayPrinter = new DisplayPrinter;

        foreach ($files as $file) {
            if ($file !== STDIN && is_dir($file)) {
                $displayPrinter->addLine("${file}: Is a directory", LineType::LITERAL);
                $displayPrinter->addLine([...static::createZeroCountArray(), 'title' => $file]);
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

    protected static function createZeroCountArray(): array
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

        $reader = new FileReader($file);

        foreach ($reader->read() as $page){
            foreach ($countModes as $countMode) {
                $counts[$countMode->name] += Counter::count($page, $countMode);
            }
        }

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
