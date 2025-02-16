# wc

## Coding Challenge 001

See https://codingchallenges.fyi/challenges/challenge-wc

This is being implemented in php - as vanilla as I can stand!

[`strict_mode`](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict) is enabled throughout.

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
$ ./ccwc file
$ ./ccwc file1 file2
$ ./ccwc *
```

> [!note]
> If more than one file is given as input, then a total is printed at the end of the output

### STDIN

This can be either via a pipe or redirector.

```bash
$ cat file | ./ccwc
$ ./ccwc < file
```

## Performance 

### ccwc vs coreutils wc

Unsurprisingly, this is roughly an order of magnitude slower than GNU coreutils.

When processing the test document, the timings are as follows:

| Implementation | Wall time |
|---|---|
| coreutils WC | 0.007 s |
| ccwc (PHP 8.4) | 0.065s |
| ccwc.php (PHP 8.4) | 0.060s |

Roughly 9x slower

### OOP vs Procedural performance

The OOP rewrite is ~10% slower than the procedural version when
processing the 335KB test file.

| Implementation | Wall time |
|---|---|
| ccwc (PHP 8.4) | 0.065s |
| ccwc.php (PHP 8.4) | 0.060s |

However, it is 20-30% faster when processing a 33MB test file

| Implementation | Wall time |
|---|---|
| ccwc (PHP 8.4) | 0.7s |
| ccwc.php (PHP 8.4) | 1.0s |
