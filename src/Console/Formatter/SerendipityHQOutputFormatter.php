<?php

namespace SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Defines common styles.
 */
class SerendipityHQOutputFormatter extends OutputFormatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct($decorated = false, $styles = [])
    {
        $styles = [
            'caution' => new OutputFormatterStyle('white', 'yellow'),
            'comment' => new OutputFormatterStyle('black', 'white'),
            'error' => new OutputFormatterStyle('white', 'red'),
            'info' => new OutputFormatterStyle('white', 'blue'),
            'note' => new OutputFormatterStyle('yellow', 'blue'),
            'success' => new OutputFormatterStyle('white', 'green'),
            'warning' => new OutputFormatterStyle('red', 'yellow'),
            'caution-nobg' => new OutputFormatterStyle('yellow'),
            'comment-nobg' => new OutputFormatterStyle('white'),
            'error-nobg' => new OutputFormatterStyle('red'),
            'info-nobg' => new OutputFormatterStyle('blue'),
            'note-nobg' => new OutputFormatterStyle('magenta'),
            'success-nobg' => new OutputFormatterStyle('green'),
            'question-nobg' => new OutputFormatterStyle('cyan'),
            'warning-nobg' => new OutputFormatterStyle('yellow')
        ];

        parent::__construct($decorated, $styles);
    }
}
