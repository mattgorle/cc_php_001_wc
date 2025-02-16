<?php

declare(strict_types=1);

namespace Gorle\Wc;

class FileReader
{
    public function __construct(protected readonly mixed $filename = '', protected readonly int $pageSize = 16 * 1_024 * 1_024) {}

    public function read(): iterable {
        $handle = is_string($this->filename) ? fopen($this->filename, 'r') : $this->filename;

        do {
            $content = fread($handle, $this->pageSize);

            if ($content === false) {
                continue;
            }

            yield $content;
        } while ($content !== false && ! feof($handle));

        fclose($handle);
    }
}
