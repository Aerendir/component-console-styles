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

namespace SerendipityHQ\Bundle\ConsoleStyles\Console\Style;

use SerendipityHQ\Bundle\ConsoleStyles\Console\ConsoleStyles;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Terminal;

/**
 * Derived from the SymfonyStyle bundled with Symfony.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 * @author Adamo Aerendir Crespi <hello@aerendir.me>
 *
 * @deprecated SerendipityHQStyle is abandoned and will not be mantained anymore. Use Symfony\Component\Console\Style\SymfonyStyle instead.
 */
class SerendipityHQStyleSF5 extends OutputStyle
{
    /** @var int */
    private const MAX_LINE_LENGTH = 120;

    /** @var string */
    private const CAUTION = 'CAUTION';

    private InputInterface $input;
    private ?SymfonyQuestionHelper $questionHelper = null;
    private ?ProgressBar $progressBar              = null;
    private int $lineLength;
    private BufferedOutput $bufferedOutput;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        trigger_error('SerendipityHQStyle is abandoned and will not be maintained anymore. Use Symfony\Component\Console\Style\SymfonyStyle instead.', E_USER_DEPRECATED);
        $this->input          = $input;
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        // Windows cmd wraps lines as soon as the terminal width is reached, whether there are following chars or not.
        $width            = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = \min($width - (int) (\DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);

        // This is required as the parent::$output is private :(
        parent::__construct($output);
    }

    /**
     * Formats a message as a block of text.
     *
     * @param array|string $messages The message to write in the block
     * @param string|null  $type     The block type (added in [] on first line)
     * @param string|null  $style    The style to apply to the whole block
     * @param string       $prefix   The prefix for the block
     * @param bool         $padding  Whether to add vertical padding
     *
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::block() instead
     */
    public function block($messages, ?string $type = null, ?string $style = null, string $prefix = ' ', bool $padding = false): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $messages = \is_array($messages) ? \array_values($messages) : [$messages];

        $this->autoPrependBlock();
        $this->writeln($this->createBlock($messages, $type, $style, $prefix, $padding, true));
        $this->newLine();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::title() instead
     */
    public function title($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->autoPrependBlock();
        $this->writeln([
            \sprintf('<fg=green>%s</>', $message),
            \sprintf('<fg=green>%s</>', \str_repeat('=', Helper::width(Helper::removeDecoration($this->getFormatter(), $message)))),
        ]);
        $this->newLine();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::section() instead
     */
    public function section($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->autoPrependBlock();
        $this->writeln([
            \sprintf('<fg=green>%s</>', $message),
            \sprintf('<fg=green>%s</>', \str_repeat('-', Helper::width(Helper::removeDecoration($this->getFormatter(), $message)))),
        ]);
        $this->newLine();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::listing() instead
     */
    public function listing(array $elements): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->autoPrependText();
        $elements = \array_map(function (string $element): string {
            return \sprintf(' * %s', $element);
        }, $elements);

        $this->writeln($elements);
        $this->newLine();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::text() instead
     */
    public function text($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->autoPrependText();

        $messages = \is_array($message) ? \array_values($message) : [$message];
        foreach ($messages as $message) {
            $this->writeln(\sprintf(' %s', $message));
        }
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::caution() instead
     */
    public function caution($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, self::CAUTION, 'caution', ' ! ', true);
    }

    /**
     * Formats a command comment as single line.
     */
    public function cautionLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, self::CAUTION, 'caution', ' ! '));
    }

    /**
     * Formats a command comment as single line without the background.
     */
    public function cautionLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, self::CAUTION, ConsoleStyles::CAUTION_NOBG, ' ! '));
    }

    /**
     * Formats a command comment as block.
     *
     * @param array|string $message
     *
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::comment() instead
     */
    public function comment($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $messages = \is_array($message) ? \array_values($message) : [$message];

        $this->block($messages, 'COMMENT', 'comment', ' // ', true);
    }

    /**
     * Formats a command comment as block.
     *
     * @param array|string $message
     */
    public function commentNoBg($message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $messages = \is_array($message) ? \array_values($message) : [$message];

        $this->block($messages, 'COMMENT', 'comment-nobg', ' // ', true);
    }

    /**
     * Formats a command comment as single line.
     */
    public function commentLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, null, 'comment', ' // '));
    }

    /**
     * Formats a command comment as single line without the background.
     */
    public function commentLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, null, 'comment-nobg', ' // '));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::error() instead
     */
    public function error($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, "\xE2\x9C\x96", 'error', ' ', true);
    }

    /**
     * Formats a command error as single line.
     */
    public function errorLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, "\xE2\x9C\x96", 'error'));
    }

    /**
     * Formats a command error as single line without the background.
     */
    public function errorLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, "\xE2\x9C\x96", 'error-nobg'));
    }

    /**
     * @deprecated no replacement suggested
     */
    public function info($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, 'INFO', 'info', ' ', true);
    }

    /**
     * Formats a command error as single line.
     */
    public function infoLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '>', 'info'));
    }

    /**
     * Formats a command error as single line without the background.
     */
    public function infoLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '>', 'info-nobg'));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::note() instead
     */
    public function note($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, 'NOTE', 'note', ' ! ', true);
    }

    /**
     * Formats a command note as single line.
     */
    public function noteLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '!', 'note'));
    }

    /**
     * Formats a command note as single line without the background.
     */
    public function noteLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '!', 'note-nobg'));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::success() instead
     */
    public function success($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, "\xE2\x9C\x94", 'success', ' ', true);
    }

    /**
     * Formats a command success as single line.
     */
    public function successLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, "\xE2\x9C\x94", 'success'));
    }

    /**
     * Formats a command success as single line without the background.
     */
    public function successLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, "\xE2\x9C\x94", 'success-nobg'));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::warning() instead
     */
    public function warning($message): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->block($message, 'WARNING', 'warning', ' ! ', true);
    }

    /**
     * Formats a command error as single line.
     */
    public function warningLine(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '!', 'warning'));
    }

    /**
     * Formats a command error as single line without the background.
     */
    public function warningLineNoBg(string $message): void
    {
        trigger_error('Use an appropriate LoggerInterface level instead.', E_USER_DEPRECATED);
        $this->writeln($this->createLine($message, '!', 'warning-nobg'));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::table() instead
     */
    public function table(array $headers, array $rows): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $style = clone Table::getStyleDefinition('symfony-style-guide');
        $style->setCellHeaderFormat('<fg=green>%s</>');

        $table = new Table($this);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);

        $table->render();
        $this->newLine();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::ask() instead
     */
    public function ask(string $question, ?string $default = null, ?callable $validator = null): mixed
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $question = new Question($question, $default);
        $question->setValidator($validator);

        return $this->askQuestion($question);
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::askHidden() instead
     */
    public function askHidden(string $question, ?callable $validator = null): mixed
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $question = new Question($question);

        $question->setHidden(true);
        $question->setValidator($validator);

        return $this->askQuestion($question);
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::confirm() instead
     */
    public function confirm(string $question, bool $default = true): bool
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);

        return (bool) $this->askQuestion(new ConfirmationQuestion($question, $default));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::choice() instead
     */
    public function choice(string $question, array $choices, mixed $default = null): mixed
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        if (null !== $default) {
            $values  = \array_flip($choices);
            $default = $values[$default];
        }

        return $this->askQuestion(new ChoiceQuestion($question, $choices, $default));
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::progressStart() instead
     */
    public function progressStart(int $max = 0): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->progressBar = $this->createProgressBar($max);
        $this->progressBar->start();
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::progressAdvance() instead
     */
    public function progressAdvance(int $step = 1): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->getProgressBar()->advance($step);
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::progressFinish() instead
     */
    public function progressFinish(): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $this->getProgressBar()->finish();
        $this->newLine(2);
        $this->progressBar = null;
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::createProgressBar() instead
     */
    public function createProgressBar(int $max = 0): ProgressBar
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        $progressBar = parent::createProgressBar($max);

        if ('\\' !== DIRECTORY_SEPARATOR) {
            $progressBar->setEmptyBarCharacter('░'); // light shade character \u2591
            $progressBar->setProgressCharacter('');
            $progressBar->setBarCharacter('▓'); // dark shade character \u2593
        }

        return $progressBar;
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::askQuestion() instead
     */
    public function askQuestion(Question $question): mixed
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        if ($this->input->isInteractive()) {
            $this->autoPrependBlock();
        }

        if ( ! $this->questionHelper instanceof SymfonyQuestionHelper) {
            $this->questionHelper = new SymfonyQuestionHelper();
        }

        $answer = $this->questionHelper->ask($this->input, $this, $question);

        if ($this->input->isInteractive()) {
            $this->newLine();
            $this->bufferedOutput->write("\n");
        }

        return $answer;
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::writeln() instead
     */
    public function writeln(string|iterable $messages, int $type = self::OUTPUT_NORMAL): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        parent::writeln($messages, $type);
        $this->bufferedOutput->writeln($this->reduceBuffer($messages), $type);
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::write() instead
     */
    public function write(string|iterable $messages, bool $newline = false, int $type = self::OUTPUT_NORMAL): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        parent::write($messages, $newline, $type);
        $this->bufferedOutput->write($this->reduceBuffer($messages), $newline, $type);
    }

    /**
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::newLine() instead
     */
    public function newLine(int $count = 1): void
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);
        parent::newLine($count);
        $this->bufferedOutput->write(\str_repeat("\n", $count));
    }

    /**
     * Returns a new instance which makes use of stderr if available.
     *
     * @deprecated use Symfony\Component\Console\Style\SymfonyStyle::getErrorStyle() instead
     */
    public function getErrorStyle(): SerendipityHQStyleSF5
    {
        trigger_error('Use Symfony\Component\Console\Style\SymfonyStyle corresponding method instead.', E_USER_DEPRECATED);

        return new self($this->input, $this->getErrorOutput());
    }

    /**
     * @deprecated No replacement suggested
     */
    public function getInput(): InputInterface
    {
        trigger_error('No replacement suggested.', E_USER_DEPRECATED);

        return $this->input;
    }

    /**
     * @deprecated no replacement suggested
     */
    public function getLineLength(): int
    {
        trigger_error('No replacement suggested.', E_USER_DEPRECATED);

        return $this->lineLength;
    }

    protected function getProgressBar(): ProgressBar
    {
        if ( ! $this->progressBar instanceof ProgressBar) {
            throw new RuntimeException('The ProgressBar is not started.');
        }

        return $this->progressBar;
    }

    protected function autoPrependBlock(): void
    {
        $chars = \substr(\str_replace(PHP_EOL, "\n", $this->bufferedOutput->fetch()), -2);

        if ( ! isset($chars[0])) {
            $this->newLine(); // empty history, so we should start with a new line.
        }
        // Prepend new line for each non LF chars (This means no blank line was output before)
        $this->newLine(2 - \substr_count($chars, "\n"));
    }

    protected function autoPrependText(): void
    {
        $fetched = $this->bufferedOutput->fetch();
        // Prepend new line if last char isn't EOL:
        if ("\n" !== \substr($fetched, -1)) {
            $this->newLine();
        }
    }

    /**
     * @param iterable<string>|string $messages
     *
     * @return string[]
     */
    protected function reduceBuffer(string|iterable $messages): array
    {
        // We need to know if the two last chars are PHP_EOL
        // Preserve the last 4 chars inserted (PHP_EOL on windows is two chars) in the history buffer
        return \array_map(function ($value): string {
            return \substr($value, -4);
        }, \array_merge([$this->bufferedOutput->fetch()], \is_string($messages) ? [$messages] : \iterator_to_array($messages, false)));
    }

    /**
     * @param null $type
     * @param null $style
     */
    protected function createBlock($messages, $type = null, $style = null, string $prefix = ' ', bool $padding = false, bool $escape = false): array
    {
        $indentLength = 0;
        $prefixLength = Helper::width(Helper::removeDecoration($this->getFormatter(), $prefix));
        $lines        = [];

        $lineIndentation = '';
        if (null !== $type) {
            $type            = \sprintf('[%s] ', $type);
            $indentLength    = \strlen($type);
            $lineIndentation = \str_repeat(' ', $indentLength);
        }

        // wrap and add newlines for each element
        foreach ($messages as $key => $message) {
            if ($escape) {
                $message = OutputFormatter::escape($message);
            }

            $lines = \array_merge($lines, \explode(PHP_EOL, \wordwrap($message, $this->lineLength - $prefixLength - $indentLength, PHP_EOL, true)));

            if ((\is_countable($messages) ? \count($messages) : 0) > 1 && $key < (\is_countable($messages) ? \count($messages) : 0) - 1) {
                $lines[] = '';
            }
        }

        $firstLineIndex = 0;
        if ($padding && $this->isDecorated()) {
            $firstLineIndex = 1;
            \array_unshift($lines, '');
            $lines[] = '';
        }

        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type . $line : $lineIndentation . $line;
            }

            $line = $prefix . $line;
            $line .= \str_repeat(' ', \max($this->lineLength - Helper::width(Helper::removeDecoration($this->getFormatter(), $line)), 0));

            if ($style) {
                $line = \sprintf('<%s>%s</>', $style, $line);
            }
        }

        return $lines;
    }

    /**
     * @param null $type
     * @param null $style
     */
    protected function createLine(string $message, $type = null, $style = null, string $prefix = '', bool $escape = false): string
    {
        if ($escape) {
            $message = OutputFormatter::escape($message);
        }

        if (null !== $type) {
            $message = \sprintf('[%s] %s', $type, $message);
        }

        $message = $prefix . $message;
        $length  = $this->lineLength - Helper::width(Helper::removeDecoration($this->getFormatter(), $message));

        if (0 < $length) {
            $message .= \str_repeat(' ', $length);
        }

        if ($style) {
            return \sprintf('<%s>%s</>', $style, $message);
        }

        return $message;
    }
}
