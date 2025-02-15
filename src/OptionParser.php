<?php

declare(strict_types=1);

namespace Gorle\Wc;

use Garden\Cli\Cli;

class OptionParser
{
    protected const DEFAULT_COUNT_MODES = [
        CountMode::CHARACTER,
        CountMode::WORD,
        CountMode::LINE,
    ];

    public static function parseOptions(array $args): Options
    {
        $cli = new Cli;

        $countModes = CountMode::cases();
        array_walk($countModes, fn (CountMode $mode) => $cli->opt($mode->value, $mode->name, false, 'boolean'));

        $parsed = $cli->parse($args, true);

        $parsedOpts = array_map(fn (string $mode) => CountMode::from($mode), array_keys($parsed->getOpts()));

        if ($parsedOpts === []) {
            $parsedOpts = static::DEFAULT_COUNT_MODES;
        }

        $parsedArgs = $parsed->getArgs();

        $options = Options::withCountModes($parsedOpts);

        array_walk($parsedArgs, fn (string $filename) => $options->addFilename($filename));

        return $options;
    }
}
