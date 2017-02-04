<?php

namespace SerendipityHQ\Bundle\ConsoleStyles\Console\Style;

use Symfony\Component\Console\Application;
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

/**
 * {@inheritdoc}
 *
 * Derived from the SymfonyStyle bundled with Symfony.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 * @author Adamo Aerendir Crespi <hello@aerendir.me>
 */
class SerendipityHQStyle extends OutputStyle
{
    const MAX_LINE_LENGTH = 120;

    private $input;
    private $questionHelper;
    private $progressBar;
    private $lineLength;
    private $bufferedOutput;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        // Windows cmd wraps lines as soon as the terminal width is reached, whether there are following chars or not.
        $this->lineLength = min($this->getTerminalWidth() - (int) (DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);

        if (class_exists('\Symfony\Component\Console\Terminal')) {
            $width = (new \Symfony\Component\Console\Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
            $this->lineLength = min($width - (int)(DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
        }

        parent::__construct($output);
    }

    /**
     * Formats a message as a block of text.
     *
     * @param string|array $messages The message to write in the block
     * @param string|null  $type     The block type (added in [] on first line)
     * @param string|null  $style    The style to apply to the whole block
     * @param string       $prefix   The prefix for the block
     * @param bool         $padding  Whether to add vertical padding
     */
    public function block($messages, $type = null, $style = null, $prefix = ' ', $padding = false)
    {
        $messages = is_array($messages) ? array_values($messages) : [$messages];

        $this->autoPrependBlock();
        $this->writeln($this->createBlock($messages, $type, $style, $prefix, $padding, true));
        $this->newLine();
    }

    /**
     * {@inheritdoc}
     */
    public function title($message)
    {
        $this->autoPrependBlock();
        $this->writeln([
            sprintf('<fg=green>%s</>', $message),
            sprintf('<fg=green>%s</>', str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ]);
        $this->newLine();
    }

    /**
     * {@inheritdoc}
     */
    public function section($message)
    {
        $this->autoPrependBlock();
        $this->writeln([
            sprintf('<fg=green>%s</>', $message),
            sprintf('<fg=green>%s</>', str_repeat('-', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ]);
        $this->newLine();
    }

    /**
     * {@inheritdoc}
     */
    public function listing(array $elements)
    {
        $this->autoPrependText();
        $elements = array_map(function ($element) {
            return sprintf(' * %s', $element);
        }, $elements);

        $this->writeln($elements);
        $this->newLine();
    }

    /**
     * {@inheritdoc}
     */
    public function text($message)
    {
        $this->autoPrependText();

        $messages = is_array($message) ? array_values($message) : [$message];
        foreach ($messages as $message) {
            $this->writeln(sprintf(' %s', $message));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function caution($message)
    {
        $this->block($message, 'CAUTION', 'caution', ' ! ', true);
    }

    /**
     * Formats a command comment as single line.
     *
     * @param string $message
     */
    public function cautionLine(string $message) {
        $this->writeln($this->createLine($message, 'CAUTION', 'caution', ' ! '));
    }

    /**
     * Formats a command comment as single line without the background.
     *
     * @param string $message
     */
    public function cautionLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'CAUTION', 'caution-nobg', ' ! '));
    }

    /**
     * Formats a command comment as block.
     *
     * @param string|array $message
     */
    public function comment($message)
    {
        $messages = is_array($message) ? array_values($message) : [$message];

        $this->block($messages, 'COMMENT', 'comment', ' // ', true);
    }

    /**
     * Formats a command comment as single line.
     *
     * @param string $message
     */
    public function commentLine(string $message) {
        $this->writeln($this->createLine($message, 'COMMENT', 'comment', ' // '));
    }

    /**
     * Formats a command comment as single line without the background.
     *
     * @param string $message
     */
    public function commentLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'COMMENT', 'comment-nobg', ' // '));
    }

    /**
     * {@inheritdoc}
     */
    public function error($message)
    {
        $this->block($message, 'ERROR', 'error', ' ', true);
    }

    /**
     * Formats a command error as single line.
     *
     * @param string $message
     */
    public function errorLine(string $message) {
        $this->writeln($this->createLine($message, 'ERROR', 'error', null));
    }

    /**
     * Formats a command error as single line without the background.
     *
     * @param string $message
     */
    public function errorLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'ERROR', 'error-nobg', null));
    }

    /**
     * {@inheritdoc}
     */
    public function info($message)
    {
        $this->block($message, 'INFO', 'info', ' ', true);
    }

    /**
     * Formats a command error as single line.
     *
     * @param string $message
     */
    public function infoLine(string $message) {
        $this->writeln($this->createLine($message, 'INFO', 'info', null));
    }

    /**
     * Formats a command error as single line without the background.
     *
     * @param string $message
     */
    public function infoLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'INFO', 'info-nobg', null));
    }

    /**
     * {@inheritdoc}
     */
    public function note($message)
    {
        $this->block($message, 'NOTE', 'note', ' ! ', true);
    }

    /**
     * Formats a command note as single line.
     *
     * @param string $message
     */
    public function noteLine(string $message) {
        $this->writeln($this->createLine($message, 'NOTE', 'note', ' ! '));
    }

    /**
     * Formats a command note as single line without the background.
     *
     * @param string $message
     */
    public function noteLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'NOTE', 'note-nobg', ' ! '));
    }

    /**
     * {@inheritdoc}
     */
    public function success($message)
    {
        $this->block($message, 'OK', 'success', ' ', true);
    }

    /**
     * Formats a command success as single line.
     *
     * @param string $message
     */
    public function successLine(string $message) {
        $this->writeln($this->createLine($message, 'OK', 'success', null));
    }

    /**
     * Formats a command success as single line without the background.
     *
     * @param string $message
     */
    public function successLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'OK', 'success-nobg', null));
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message)
    {
        $this->block($message, 'WARNING', 'warning', ' ! ', true);
    }

    /**
     * Formats a command error as single line.
     *
     * @param string $message
     */
    public function warningLine(string $message) {
        $this->writeln($this->createLine($message, 'WARNING', 'warning', ' ! '));
    }

    /**
     * Formats a command error as single line without the background.
     *
     * @param string $message
     */
    public function warningLineNoBg(string $message) {
        $this->writeln($this->createLine($message, 'WARNING', 'warning-nobg', ' ! '));
    }

    /**
     * {@inheritdoc}
     */
    public function table(array $headers, array $rows)
    {
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
     * {@inheritdoc}
     */
    public function ask($question, $default = null, $validator = null)
    {
        $question = new Question($question, $default);
        $question->setValidator($validator);

        return $this->askQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    public function askHidden($question, $validator = null)
    {
        $question = new Question($question);

        $question->setHidden(true);
        $question->setValidator($validator);

        return $this->askQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    public function confirm($question, $default = true)
    {
        return $this->askQuestion(new ConfirmationQuestion($question, $default));
    }

    /**
     * {@inheritdoc}
     */
    public function choice($question, array $choices, $default = null)
    {
        if (null !== $default) {
            $values = array_flip($choices);
            $default = $values[$default];
        }

        return $this->askQuestion(new ChoiceQuestion($question, $choices, $default));
    }

    /**
     * {@inheritdoc}
     */
    public function progressStart($max = 0)
    {
        $this->progressBar = $this->createProgressBar($max);
        $this->progressBar->start();
    }

    /**
     * {@inheritdoc}
     */
    public function progressAdvance($step = 1)
    {
        $this->getProgressBar()->advance($step);
    }

    /**
     * {@inheritdoc}
     */
    public function progressFinish()
    {
        $this->getProgressBar()->finish();
        $this->newLine(2);
        $this->progressBar = null;
    }

    /**
     * {@inheritdoc}
     */
    public function createProgressBar($max = 0)
    {
        $progressBar = parent::createProgressBar($max);

        if ('\\' !== DIRECTORY_SEPARATOR) {
            $progressBar->setEmptyBarCharacter('░'); // light shade character \u2591
            $progressBar->setProgressCharacter('');
            $progressBar->setBarCharacter('▓'); // dark shade character \u2593
        }

        return $progressBar;
    }

    /**
     * @param Question $question
     *
     * @return string
     */
    public function askQuestion(Question $question)
    {
        if ($this->input->isInteractive()) {
            $this->autoPrependBlock();
        }

        if (!$this->questionHelper) {
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
     * {@inheritdoc}
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        parent::writeln($messages, $type);
        $this->bufferedOutput->writeln($this->reduceBuffer($messages), $type);
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        parent::write($messages, $newline, $type);
        $this->bufferedOutput->write($this->reduceBuffer($messages), $newline, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function newLine($count = 1)
    {
        parent::newLine($count);
        $this->bufferedOutput->write(str_repeat("\n", $count));
    }

    /**
     * Returns a new instance which makes use of stderr if available.
     *
     * @return self
     */
    public function getErrorStyle()
    {
        if (method_exists($this, 'getErrorOutput')) {
            return new self($this->input, $this->getErrorOutput());
        }

        return $this;
    }

    /**
     * @return ProgressBar
     */
    protected function getProgressBar()
    {
        if (!$this->progressBar) {
            throw new RuntimeException('The ProgressBar is not started.');
        }

        return $this->progressBar;
    }

    /**
     * @return int
     */
    protected function getTerminalWidth()
    {
        $application = new Application();
        $dimensions = $application->getTerminalDimensions();

        return $dimensions[0] ?: self::MAX_LINE_LENGTH;
    }

    protected function autoPrependBlock()
    {
        $chars = substr(str_replace(PHP_EOL, "\n", $this->bufferedOutput->fetch()), -2);

        if (!isset($chars[0])) {
            return $this->newLine(); //empty history, so we should start with a new line.
        }
        //Prepend new line for each non LF chars (This means no blank line was output before)
        $this->newLine(2 - substr_count($chars, "\n"));
    }

    protected function autoPrependText()
    {
        $fetched = $this->bufferedOutput->fetch();
        //Prepend new line if last char isn't EOL:
        if ("\n" !== substr($fetched, -1)) {
            $this->newLine();
        }
    }

    /**
     * @param $messages
     * @return array
     */
    protected function reduceBuffer($messages)
    {
        // We need to know if the two last chars are PHP_EOL
        // Preserve the last 4 chars inserted (PHP_EOL on windows is two chars) in the history buffer
        return array_map(function ($value) {
            return substr($value, -4);
        }, array_merge([$this->bufferedOutput->fetch()], (array) $messages));
    }

    /**
     * @param $messages
     * @param null $type
     * @param null $style
     * @param string $prefix
     * @param bool $padding
     * @param bool $escape
     * @return array
     */
    protected function createBlock($messages, $type = null, $style = null, $prefix = ' ', $padding = false, $escape = false)
    {
        $indentLength = 0;
        $prefixLength = Helper::strlenWithoutDecoration($this->getFormatter(), $prefix);
        $lines = [];

        $lineIndentation = '';
        if (null !== $type) {
            $type = sprintf('[%s] ', $type);
            $indentLength = strlen($type);
            $lineIndentation = str_repeat(' ', $indentLength);
        }

        // wrap and add newlines for each element
        foreach ($messages as $key => $message) {
            if ($escape) {
                $message = OutputFormatter::escape($message);
            }

            $lines = array_merge($lines, explode(PHP_EOL, wordwrap($message, $this->lineLength - $prefixLength - $indentLength, PHP_EOL, true)));

            if (count($messages) > 1 && $key < count($messages) - 1) {
                $lines[] = '';
            }
        }

        $firstLineIndex = 0;
        if ($padding && $this->isDecorated()) {
            $firstLineIndex = 1;
            array_unshift($lines, '');
            $lines[] = '';
        }

        foreach ($lines as $i => &$line) {
            if (null !== $type) {
                $line = $firstLineIndex === $i ? $type.$line : $lineIndentation.$line;
            }

            $line = $prefix.$line;
            $line .= str_repeat(' ', $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $line));

            if ($style) {
                $line = sprintf('<%s>%s</>', $style, $line);
            }
        }

        return $lines;
    }

    protected function createLine($message, $type = null, $style = null, $prefix = '', $escape = false)
    {
        if ($escape) {
            $message = OutputFormatter::escape($message);
        }

        if (null !== $type) {
            $message = sprintf('[%s] %s', $type, $message);
        }

        $message = $prefix.$message;
        $message .= str_repeat(' ', $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $message));

        if ($style) {
            $message = sprintf('<%s>%s</>', $style, $message);
        }

        return $message;
    }
}
