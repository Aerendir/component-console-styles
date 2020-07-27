<?php

/*
 * This file is part of the Serendipity HQ Console Styles Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter;

use SerendipityHQ\Bundle\ConsoleStyles\Console\ConsoleColors;
use SerendipityHQ\Bundle\ConsoleStyles\Console\ConsoleStyles;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Defines common styles.
 */
final class SerendipityHQOutputFormatter extends OutputFormatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct($decorated = false, $styles = [])
    {
        $styles = \array_merge([
            ConsoleStyles::CAUTION       => new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::YELLOW),
            ConsoleStyles::COMMENT       => new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::WHITE),
            ConsoleStyles::ERROR         => new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::RED),
            ConsoleStyles::INFO          => new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::BLUE),
            ConsoleStyles::NOTE          => new OutputFormatterStyle(ConsoleColors::YELLOW, ConsoleColors::BLUE),
            ConsoleStyles::SUCCESS       => new OutputFormatterStyle(ConsoleColors::BLACK, ConsoleColors::GREEN),
            ConsoleStyles::WARNING       => new OutputFormatterStyle(ConsoleColors::RED, ConsoleColors::YELLOW),
            ConsoleStyles::CAUTION_NOBG  => new OutputFormatterStyle(ConsoleColors::YELLOW),
            ConsoleStyles::COMMENT_NOBG  => new OutputFormatterStyle(ConsoleColors::WHITE),
            ConsoleStyles::ERROR_NOBG    => new OutputFormatterStyle(ConsoleColors::RED),
            ConsoleStyles::INFO_NOBG     => new OutputFormatterStyle(ConsoleColors::BLUE),
            ConsoleStyles::NOTE_NOBG     => new OutputFormatterStyle(ConsoleColors::MAGENTA),
            ConsoleStyles::QUESTION_NOBG => new OutputFormatterStyle(ConsoleColors::CYAN),
            ConsoleStyles::SUCCESS_NOBG  => new OutputFormatterStyle(ConsoleColors::GREEN),
            ConsoleStyles::WARNING_NOBG  => new OutputFormatterStyle(ConsoleColors::YELLOW),
        ], $styles);

        parent::__construct($decorated, $styles);
    }
}
