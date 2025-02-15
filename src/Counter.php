<?php

namespace Gorle\Wc;

class Counter {
    public static function count(string $contents, CountMode $countMode): int
    {
        return match($countMode) {
            CountMode::CHARACTER => strlen($contents),
            CountMode::MB_CHARACTER => mb_strlen($contents),
            CountMode::LINE => count(preg_split('(\r\n|\r|\n)', $contents)) - 1,
            CountMode::WORD => count(preg_split('/[^\s]+/', $contents)) - 1
        };
    }
}
