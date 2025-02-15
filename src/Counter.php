<?php

namespace Gorle\Wc;

class Counter
{
    protected static $lastFourBytes = '';

    public static function count(string $contents, CountMode $countMode): int
    {
        return match ($countMode) {
            CountMode::CHARACTER => strlen($contents),
            CountMode::MB_CHARACTER => mb_strlen($contents),
            CountMode::LINE => count(preg_split('(\r\n|\r|\n)', $contents)) - 1,
            CountMode::WORD => static::countWords($contents)
        };
    }

    public static function reset(): void
    {
        static::$lastFourBytes = '';
    }

    protected static function countWords(string $contents): int
    {
        $count = count(preg_split('/[^\s]+/', static::$lastFourBytes.$contents)) - 1;

        $offset = static::$lastFourBytes === '' ? 0 : 1;

        static::$lastFourBytes = substr($contents, -4);

        return $count - $offset;
    }
}
