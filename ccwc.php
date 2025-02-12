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

function countFile($filename, $modes): array {
	$contents = file_get_contents($filename);

	$output = [];

	if (array_key_exists(MODE_LINE, $modes)) {
		$output[] = count(explode(PHP_EOL, $contents)) - 1;
	}

	if (array_key_exists(MODE_WORD, $modes)) {
		$output[] = count(preg_split('/[\s]+/', $contents)) - 1;
	}

	if (array_key_exists(MODE_CHARACTER, $modes)) {
		$output[] = strlen($contents);
	}

	if (array_key_exists(MODE_MB_CHARACTER, $modes)) {
		$output[] = mb_strlen($contents);
	}

	$output[] = $filename;

	return $output;
}

foreach ($filenames as $filename) {
	$lines[] = implode("\t", countFile($filename, $modes));
}

echo implode(PHP_EOL, $lines);
echo PHP_EOL;
