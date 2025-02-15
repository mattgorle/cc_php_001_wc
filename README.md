# wc

## Coding Challenge 001

See https://codingchallenges.fyi/challenges/challenge-wc

This is being implemented in php - as vanilla as I can stand!

## Requirements

- [PHP](https://php.net) 8.4
- [composer](https://getcomposer.org)

## Installation

```bash
$ git clone https://github.com/mattgorle/cc_php_001_wc
$ cd cc_php_001_wc
$ composer install
```

## Running Tests

```bash
vendor/bin/phpunit
```

## Counting modes

As of now, this supports the following modes:

1. `-c`: Character count
2. `-m`: Multi-byte character count
3. `-l`: Line count
4. `-w`: Word count

## Input modes

Input can by given by way of filenames on the CLI or via STDIN.

### Filenames

One or more file names can be provided as an input argument.  Shell expansion works as expected.

```bash
$ ./ccwc.php file
$ ./ccwc.php file1 file2
$ ./ccwc.php *
```

> [!note]
> If more than one file is given as input, then a total is printed at the end of the output

### STDIN

This can be either via a pipe or redirector.

```bash
$ cat file | ./ccwc.php
$ ./ccwc.php < file
```

## Performance vs original wc

Unsurprisingly, this is roughly an order of magnitude slower than GNU coreutils.

When processing the test document, the timings are as follows:

| Implementation | Wall time |
|---|---|
| coreutils WC | 0.007 s |
| ccwc.php (PHP 8.4) | 0.040s |

Roughly 6x slower
