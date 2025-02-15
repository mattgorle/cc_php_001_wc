<?php

declare(strict_types=1);

namespace Gorle\Wc;

enum CountMode: string
{
    case MB_CHARACTER = 'm';
    case CHARACTER = 'c';
    case LINE = 'l';
    case WORD = 'w';
}
