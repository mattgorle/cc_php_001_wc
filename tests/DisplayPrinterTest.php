<?php

declare(strict_types=1);

use Gorle\Wc\CountMode;
use Gorle\Wc\DisplayPrinter;
use Gorle\Wc\LineType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DisplayPrinterTest extends TestCase
{
    #[DataProvider('displayPrinterProvider')]
    public function test_fields_are_in_the_right_order_and_have_the_right_width(array $lines, string $expectedOutput)
    {
        /*
         * For reference, the correct order is:
         * Lines, Words, Characters
         *
         * The column width for all rows is 1 + the widest column
         * All columns are right aligned
         */

        $printer = new DisplayPrinter;

        foreach ($lines as $line) {
            $printer->addLine($line['data'], $line['type']);
        }

        $output = $printer->print();
        $this->assertEquals($expectedOutput, $output);
    }

    public static function displayPrinterProvider(): iterable
    {
        yield '1.1: multiple count lines (ascending)' => [
            [
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 10,
                        CountMode::WORD->name => 125,
                        CountMode::CHARACTER->name => 750,
                        'title' => 'line1',
                    ],
                ],
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 100,
                        CountMode::CHARACTER->name => 7500,
                        CountMode::WORD->name => 1250,
                        'title' => 'line2',
                    ],
                ],
            ],
            <<<'EOF'
  10  125  750 line1
 100 1250 7500 line2
 110 1375 8250 total

EOF
        ];

        yield '1.2: multiple count lines (random)' => [
            [
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 10,
                        CountMode::WORD->name => 125,
                        CountMode::CHARACTER->name => 750,
                        'title' => 'line1',
                    ],
                ],
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 100,
                        CountMode::CHARACTER->name => 7500,
                        CountMode::WORD->name => 1250,
                        'title' => 'line2',
                    ],
                ],
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 5,
                        CountMode::CHARACTER->name => 5,
                        CountMode::WORD->name => 5,
                        'title' => 'line3',
                    ],
                ],
            ],
            <<<'EOF'
  10  125  750 line1
 100 1250 7500 line2
   5    5    5 line3
 115 1380 8255 total

EOF
        ];

        yield '2: single count line' => [
            [
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 10,
                        CountMode::WORD->name => 125,
                        CountMode::CHARACTER->name => 750,
                        'title' => 'line1',
                    ],
                ],
            ],
            <<<'EOF'
 10 125 750 line1

EOF
        ];

        yield '3.1: literal with single count line' => [
            [
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 10,
                        CountMode::WORD->name => 125,
                        CountMode::CHARACTER->name => 750,
                        'title' => 'line1',
                    ],
                ],
                [
                    'type' => LineType::LITERAL,
                    'data' => 'Foo'
                ]
            ],
            <<<'EOF'
 10 125 750 line1
Foo

EOF
        ];

        yield '3.2: literal with multiple count lines' => [
            [
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 10,
                        CountMode::WORD->name => 125,
                        CountMode::CHARACTER->name => 750,
                        'title' => 'line1',
                    ],
                ],
                [
                    'type' => LineType::LITERAL,
                    'data' => 'Foo'
                ],
                [
                    'type' => LineType::COUNT,
                    'data' => [
                        CountMode::LINE->name => 100,
                        CountMode::CHARACTER->name => 7500,
                        CountMode::WORD->name => 1250,
                        'title' => 'line2',
                    ],
                ],
            ],
            <<<'EOF'
  10  125  750 line1
Foo
 100 1250 7500 line2
 110 1375 8250 total

EOF
        ];

    }
}
