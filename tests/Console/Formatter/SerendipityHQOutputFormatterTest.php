<?php

declare(strict_types=1);

/*
 * This file is part of the Serendipity HQ Console Styles Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Bundle\ConsoleStyles\Tests\Console\Formatter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SerendipityHQ\Bundle\ConsoleStyles\Console\ConsoleColors;
use SerendipityHQ\Bundle\ConsoleStyles\Console\ConsoleStyles;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter\SerendipityHQOutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

final class SerendipityHQOutputFormatterTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function provideStyles(): iterable
    {
        $styles = [
            ConsoleStyles::CAUTION,
            ConsoleStyles::COMMENT,
            ConsoleStyles::ERROR,
            ConsoleStyles::INFO,
            ConsoleStyles::NOTE,
            ConsoleStyles::SUCCESS,
            ConsoleStyles::WARNING,
            ConsoleStyles::CAUTION_NOBG,
            ConsoleStyles::COMMENT_NOBG,
            ConsoleStyles::ERROR_NOBG,
            ConsoleStyles::INFO_NOBG,
            ConsoleStyles::NOTE_NOBG,
            ConsoleStyles::QUESTION_NOBG,
            ConsoleStyles::SUCCESS_NOBG,
            ConsoleStyles::WARNING_NOBG,
        ];

        foreach ($styles as $style) {
            yield $style => [$style];
        }
    }

    #[DataProvider('provideStyles')]
    public function testDefinesTheStyle(string $style): void
    {
        $formatter = new SerendipityHQOutputFormatter();

        self::assertTrue($formatter->hasStyle($style));
    }

    public function testAppliesTheColorsOfTheStyle(): void
    {
        $formatter = new SerendipityHQOutputFormatter(true);

        $expected = (new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::RED))->apply('Boom');

        self::assertSame($expected, $formatter->format('<error>Boom</error>'));
        self::assertStringContainsString("\033[", (string) $formatter->format('<success-nobg>Done</success-nobg>'));
    }

    public function testDoesNotDecorateWhenNotDecorated(): void
    {
        $formatter = new SerendipityHQOutputFormatter(false);

        self::assertFalse($formatter->isDecorated());
        self::assertSame('Boom', $formatter->format('<error>Boom</error>'));
    }

    public function testPassedStylesOverrideTheDefaultOnes(): void
    {
        $custom    = new OutputFormatterStyle(ConsoleColors::WHITE, ConsoleColors::MAGENTA);
        $formatter = new SerendipityHQOutputFormatter(true, [
            ConsoleStyles::ERROR => $custom,
            'custom'             => $custom,
        ]);

        self::assertSame($custom, $formatter->getStyle(ConsoleStyles::ERROR));
        self::assertSame($custom, $formatter->getStyle('custom'));
        self::assertTrue($formatter->hasStyle(ConsoleStyles::WARNING));
    }
}
