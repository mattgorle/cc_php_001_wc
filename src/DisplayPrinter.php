<?php

declare(strict_types=1);

namespace Gorle\Wc;

class DisplayPrinter
{
    protected int $maxCount = 0;

    protected array $activeModes = [];

    protected array $possibleModes = [];

    public function __construct(protected array $lines = [], protected array $widths = [])
    {
        foreach (CountMode::cases() as $case) {
            $this->widths[$case->name] = 0;
        }

        $this->possibleModes = array_map(fn (CountMode $mode) => $mode->name, CountMode::cases());
    }

    public function addLine(array|string $line, LineType $lineType = LineType::COUNT): static
    {
        $this->lines[] = [
            'type' => $lineType,
            'data' => $line,
        ];

        if ($lineType === LineType::COUNT && is_array($line)) {
            $numericValues = array_filter($line, fn ($item) => is_numeric($item));
            $this->maxCount = max([$this->maxCount, ...$numericValues]);

            $keys = array_keys($line);
            $activeModes = array_unique([...$this->activeModes, ...$keys]);
            $this->activeModes = array_filter($activeModes, fn ($mode) => in_array($mode, $this->possibleModes));
        }

        return $this;
    }

    protected function reorderArray($input): array
    {
        return [
            $input[CountMode::LINE->name] ?? null,
            $input[CountMode::WORD->name] ?? null,
            $input[CountMode::CHARACTER->name] ?? null,
            $input[CountMode::MB_CHARACTER->name] ?? null,
            $input['title'] ?? null,
        ];
    }

    protected function findColumnWidth(): void
    {
        foreach ($this->activeModes as $mode) {
            $this->widths[$mode] = strlen((string) $this->maxCount);
        }
    }

    protected function calculateTotals(): array
    {
        $totals = [];

        $countLines = array_filter($this->lines, fn ($line) => $line['type'] === LineType::COUNT);
        $countLines = array_map(fn ($line) => $line['data'], $countLines);

        foreach ($this->activeModes as $mode) {
            $totals[$mode] = array_sum(array_column($countLines, $mode));
        }

        $totals['title'] = 'total';

        return $totals;
    }

    public function print(): string
    {
        $this->findColumnWidth();
        $widths = array_filter($this->reorderArray($this->widths));

        $formatString = str_repeat('%%%sd ', count($widths));
        $formatString = sprintf($formatString, ...$widths);
        $formatString .= '%s';

        $output = '';

        foreach ($this->lines as $line) {
            switch ($line['type']) {
                case LineType::COUNT:
                    $output .= sprintf($formatString.PHP_EOL, ...array_filter($this->reorderArray($line['data']), fn ($item) => $item !== null));
                    break;
                case LineType::LITERAL:
                    $output .= sprintf('%s'.PHP_EOL, $line['data']);
                    break;
            }
        }

        if (count(array_filter($this->lines, fn ($line) => $line['type'] === LineType::COUNT)) > 1) {
            $output .= sprintf($formatString.PHP_EOL, ...array_filter($this->reorderArray($this->calculateTotals())));
        }

        return $output;
    }
}
