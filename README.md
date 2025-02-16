# wc

## Coding Challenge 001

See https://codingchallenges.fyi/challenges/challenge-wc

This has been implemented in php - as vanilla as I can stand!

Initially, this was implemented as a procedural single-file solution, then re-implemented using OOP.

Both versions are included in this repository:

- Procedural version: `ccwc.php`
- OOP version: `ccwc`

[`strict_mode`](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict) is enabled throughout.

## Requirements

- [PHP](https://php.net) >= 8.1
- [composer](https://getcomposer.org)

> [!note]
> `ccwc` has been developed using PHP 8.4, but is backwards compatible as far as PHP 8.1

## Installation

```bash
$ git clone https://github.com/mattgorle/cc_php_001_wc
$ cd cc_php_001_wc
$ composer install
```

## Running Tests

```bash
$ vendor/bin/phpunit
```

> [!note]
> 1. Tests will only run on PHP 8.3 and above
> 2. A deprecation notice will affect one of the OptionParser tests  
>    Both the test and the class are behaving as expected and use no deprecated behaviour  
>    The root cause has been identified within a third-party library and a pull request opened to correct the issue

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

However, it is 20-30% faster when processing a 33MB test file.

| Implementation | Wall time |
|---|---|
| ccwc (PHP 8.4) | 0.7s |
| ccwc.php (PHP 8.4) | 1.0s |

## Differences between the Procedural and OOP versions

### Procedural
- No automated test coverage
- Easier to understand the code at a glance
- No large file support (limited to roughly system RAM / 4)
- Output is less authentic when compared to coreutils `wc`
- Faster than OOP on smaller files, slower on larger files
- Reads the entire file into memory for processing
- Uses no third-party libraries

### OOP
- Good level of automated test coverage
- Supports files of potentially any size (files are loaded in 16MB pages)
- Output closely matches coreutils `wc`
- Delegates CLI argument parsing to [`vanilla/garden-cli`](https://github.com/vanilla/garden-cli)
