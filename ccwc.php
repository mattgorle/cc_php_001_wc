#!/usr/bin/env php
<?php

const MODE_MB_CHARACTER = 'm';
const MODE_CHARACTER = 'c';
const MODE_LINE = 'l';
const MODE_WORD = 'w';

$filenamePos = null;
$modes = getopt('clmw', [], $filenamePos);
$filename = array_slice($argv, $filenamePos);

$contents = file_get_contents($filename[0]);

$output = [];

if (array_key_exists(MODE_LINE, $modes)) {
	$output[] = count(array_filter(explode(PHP_EOL, $contents), 'strlen'));
}

if (array_key_exists(MODE_CHARACTER, $modes)) {
	$output[] = strlen($contents);
}

if (array_key_exists(MODE_MB_CHARACTER, $modes)) {
	$output[] = mb_strlen($contents);
}

$output[] = $filename[0];

echo implode(' ', $output);
echo PHP_EOL;
