#!/usr/bin/env php
<?php

const MODE_MB_CHARACTER = 'm';
const MODE_CHARACTER = 'c';
const MODE_LINE = 'l';
const MODE_WORD = 'w';

const MODE_DEFAULT = [
	MODE_CHARACTER => false,
	MODE_WORD => false,
	MODE_LINE => false,
];

$filenamePos = null;
$modes = getopt('clmw', [], $filenamePos);

if ($modes === []) {
	$modes = MODE_DEFAULT;
}

$filenames = array_slice($argv, $filenamePos);

function countFile($contents, $modes): array 
{
	$output = [];
 
	if (array_key_exists(MODE_LINE, $modes)) {
		$output[MODE_LINE] = count(explode(PHP_EOL, $contents)) - 1;
	}

	if (array_key_exists(MODE_WORD, $modes)) {
		$output[MODE_WORD] = count(preg_split('/[\s]+/', $contents)) - 1;
	}

	if (array_key_exists(MODE_CHARACTER, $modes)) {
		$output[MODE_CHARACTER] = strlen($contents);
	}

	if (array_key_exists(MODE_MB_CHARACTER, $modes)) {
		$output[MODE_MB_CHARACTER] = mb_strlen($contents);
	}

	return $output;
}

function formatOutputLine(array $countData, ?string $filename = null): string
{
		return implode(
			"\t", 
			array_filter([
				$countData[MODE_LINE] ?? null,
				$countData[MODE_WORD] ?? null,
				$countData[MODE_CHARACTER] ?? null,
				$countData[MODE_MB_CHARACTER] ?? null,
				$filename ?? null
			])
		);
}

function countMultipleFiles(array $filenames, array $modes): array
{
	$totals = [
		MODE_LINE => 0,
		MODE_WORD => 0,
		MODE_CHARACTER => 0,
		MODE_MB_CHARACTER => 0
	];

	foreach ($filenames as $filename) {
		$contents = file_get_contents($filename);
		$counts = countFile($contents, $modes);

		foreach ($counts as $mode => $count) {
			$totals[$mode] += $count;
		}

		$lines[] = formatOutputLine($counts, $filename);
	}

	if (count($filenames) > 1) {
		$lines[] = formatOutputLine($totals, 'total');
	}

	return $lines;
}

function countStdin(array $modes): array
{
	$contents = stream_get_contents(STDIN);

	$counts = countFile($contents, $modes);

	return [formatOutputLine($counts)];
}

if (count($filenames) > 0) {
	$lines = countMultipleFiles($filenames, $modes);
} else {
	$lines = countStdin($modes);
}

echo implode(PHP_EOL, $lines);
echo PHP_EOL;
