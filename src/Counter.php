<?php

namespace Gorle\Wc;

class Counter {
    public function count(string $contents, CountMode $countMode): int
    {
        if ($countMode === CountMode::CHARACTER) {
            return strlen($contents);
        }

        if ($countMode === CountMode::MB_CHARACTER) {
            return mb_strlen($contents);
        }

        if ($countMode === CountMode::LINE) {
            return count(preg_split('(\r\n|\r|\n)', $contents)) - 1;
        }

        if ($countMode === CountMode::WORD) {
            return count(preg_split('/[^\s]+/', $contents)) - 1;
        }
    }
}
