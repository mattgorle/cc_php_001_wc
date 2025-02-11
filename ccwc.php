#!/usr/bin/env php
<?php

$mode = $argv[1];
$filename = $argv[2];
$contents = file_get_contents($filename);

$output = [];

$output[] = strlen($contents);
$output[] = $filename;

echo implode(' ', $output);
echo PHP_EOL;
