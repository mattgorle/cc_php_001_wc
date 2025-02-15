<?php

declare(strict_types=1);

namespace Gorle\Wc;

class Options
{
    protected array $filenames = [];

    public function __construct(protected array $countModes = [])
    {
        $this->addCountModes($countModes);
    }

    public static function withCountModes(array $countModes): static
    {
        return new static($countModes);
    }

    public static function create(): static
    {
        return new static;
    }

    public function addCountMode(CountMode $countMode): static
    {
        if (array_search($countMode, $this->countModes) === false) {
            $this->countModes[] = $countMode;
        }

        return $this;
    }

    public function addCountModes(array $countModes): static
    {
        foreach ($this->ensureOnlyCountModeInstances($countModes) as $countMode) {
            $this->addCountMode($countMode);
        }

        return $this;
    }

    public function addFilename(string $filename): static
    {
        if (array_search($filename, $this->filenames) === false) {
            $this->filenames[] = $filename;
        }

        return $this;
    }

    public function countModes(): array
    {
        return $this->countModes;
    }

    public function filenames(): array
    {
        return $this->filenames;
    }

    public function inputMode(): InputMode
    {
        if ($this->filenames === []) {
            return InputMode::STDIN;
        }

        return InputMode::FILE;
    }

    public function hasCountMode(CountMode $countMode): bool
    {
        return array_search($countMode, $this->countModes) !== false;
    }

    protected function ensureOnlyCountModeInstances(array $input): array
    {
        return array_filter($input, fn ($item) => $item instanceof CountMode);
    }
}
