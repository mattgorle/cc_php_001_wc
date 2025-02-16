<?php

declare(strict_types=1);

namespace Gorle\Wc;

class Wc
{
    protected static ?DisplayPrinter $displayPrinter = null;

    protected static ?Options $options = null;

    public static function main(): int
    {
        static::$displayPrinter = new DisplayPrinter;

        static::$options = OptionParser::parseOptions($GLOBALS['argv']);

        $files = static::$options->inputMode() === InputMode::FILE ? static::$options->filenames() : [STDIN];

        static::countMultipleFiles($files);

        echo static::$displayPrinter->print();

        return 0;
    }

    protected static function countMultipleFiles(array $files): void
    {
        array_walk($files, 'static::handleEachFile');
    }

    protected static function handleEachFile($file)
    {
        if ($file !== STDIN && is_dir($file)) {
            static::$displayPrinter->addLine("${file}: Is a directory", LineType::LITERAL);
            static::$displayPrinter->addLine([...static::createZeroCountArray(), 'title' => $file]);
        }

        if ($file !== STDIN && ! is_file($file)) {
            return;
        }

        $count = [...static::countFile($file), 'title' => is_string($file) ? $file : ''];

        static::$displayPrinter->addLine($count, LineType::COUNT);
    }

    protected static function countFile($file): array
    {
        $countModes = static::$options->countModes();
        $counts = static::initialiseModesArray();

        Counter::reset();

        $reader = new FileReader($file);

        foreach ($reader->read() as $page) {
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

    protected static function createZeroCountArray(): array
    {
        $countModes = static::$options->countModes();
        $counts = static::initialiseModesArray();

        foreach ($countModes as $countMode) {
            $counts[$countMode->name] += 0;
        }

        return $counts;
    }
}
