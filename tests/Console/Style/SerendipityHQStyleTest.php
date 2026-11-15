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

namespace SerendipityHQ\Bundle\ConsoleStyles\Tests\Console\Style;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter\SerendipityHQOutputFormatter;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Style\SerendipityHQStyle;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

#[IgnoreDeprecations]
final class SerendipityHQStyleTest extends TestCase
{
    private const LINE_LENGTH = 80;

    private string|false $columns;
    private ArrayInput $input;
    private BufferedOutput $output;
    private SerendipityHQStyle $style;

    #[\Override]
    protected function setUp(): void
    {
        $this->columns = \getenv('COLUMNS');
        \putenv('COLUMNS=' . self::LINE_LENGTH);

        $this->input = new ArrayInput([]);
        $this->input->setInteractive(false);

        $this->output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, false, new SerendipityHQOutputFormatter());
        $this->style  = new SerendipityHQStyle($this->input, $this->output);
    }

    #[\Override]
    protected function tearDown(): void
    {
        \putenv(false === $this->columns ? 'COLUMNS' : 'COLUMNS=' . $this->columns);
    }

    public function testExposesInputAndLineLength(): void
    {
        self::assertSame($this->input, $this->style->getInput());
        self::assertSame(self::LINE_LENGTH, $this->style->getLineLength());
    }

    public function testTriggersADeprecation(): void
    {
        $this->expectUserDeprecationMessage('SerendipityHQStyle is abandoned and will not be maintained anymore. Use Symfony\Component\Console\Style\SymfonyStyle instead.');

        new SerendipityHQStyle($this->input, $this->output);
    }

    public function testTitleIsUnderlinedWithEquals(): void
    {
        $this->style->title('Hello');

        self::assertSame("\n\n\nHello\n=====\n\n", $this->output->fetch());
    }

    public function testSectionIsUnderlinedWithDashes(): void
    {
        $this->style->section('Hello world');

        self::assertSame("\n\n\nHello world\n-----------\n\n", $this->output->fetch());
    }

    public function testTitleUnderlineIgnoresTheDecoration(): void
    {
        $this->style->title('<info>Hi</info>');

        self::assertStringContainsString("\nHi\n==\n", $this->output->fetch());
    }

    public function testListing(): void
    {
        $this->style->listing(['first', 'second']);

        self::assertSame("\n * first\n * second\n\n", $this->output->fetch());
    }

    public function testText(): void
    {
        $this->style->text(['first', 'second']);

        self::assertSame("\n first\n second\n", $this->output->fetch());
    }

    public function testBlockIsPaddedToTheLineLength(): void
    {
        $this->style->block('A message', 'TYPE', null, ' > ');

        $lines = \explode("\n", $this->output->fetch());

        self::assertContains(\str_pad(' > [TYPE] A message', self::LINE_LENGTH), $lines);
    }

    public function testBlockWrapsLongMessages(): void
    {
        $this->style->block(\str_repeat('word ', 40));

        $lines = \array_filter(\explode("\n", $this->output->fetch()), static fn (string $line): bool => '' !== \trim($line));

        self::assertGreaterThan(1, \count($lines));
        foreach ($lines as $line) {
            self::assertSame(self::LINE_LENGTH, \mb_strlen($line));
        }
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideBlocks(): iterable
    {
        yield 'caution' => ['caution', ' ! [CAUTION] The message'];
        yield 'comment' => ['comment', ' // [COMMENT] The message'];
        yield 'commentNoBg' => ['commentNoBg', ' // [COMMENT] The message'];
        yield 'error' => ['error', " [\u{2716}] The message"];
        yield 'info' => ['info', ' [INFO] The message'];
        yield 'note' => ['note', ' ! [NOTE] The message'];
        yield 'success' => ['success', " [\u{2714}] The message"];
        yield 'warning' => ['warning', ' ! [WARNING] The message'];
    }

    #[DataProvider('provideBlocks')]
    public function testBlocks(string $method, string $expected): void
    {
        $this->style->{$method}('The message');

        self::assertStringContainsString($expected, $this->output->fetch());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideLines(): iterable
    {
        yield 'cautionLine' => ['cautionLine', ' ! [CAUTION] The message'];
        yield 'cautionLineNoBg' => ['cautionLineNoBg', ' ! [CAUTION] The message'];
        yield 'commentLine' => ['commentLine', ' // The message'];
        yield 'commentLineNoBg' => ['commentLineNoBg', ' // The message'];
        yield 'errorLine' => ['errorLine', "[\u{2716}] The message"];
        yield 'errorLineNoBg' => ['errorLineNoBg', "[\u{2716}] The message"];
        yield 'infoLine' => ['infoLine', '[>] The message'];
        yield 'infoLineNoBg' => ['infoLineNoBg', '[>] The message'];
        yield 'noteLine' => ['noteLine', '[!] The message'];
        yield 'noteLineNoBg' => ['noteLineNoBg', '[!] The message'];
        yield 'successLine' => ['successLine', "[\u{2714}] The message"];
        yield 'successLineNoBg' => ['successLineNoBg', "[\u{2714}] The message"];
        yield 'warningLine' => ['warningLine', '[!] The message'];
        yield 'warningLineNoBg' => ['warningLineNoBg', '[!] The message'];
    }

    #[DataProvider('provideLines')]
    public function testLines(string $method, string $expected): void
    {
        $this->style->{$method}('The message');

        self::assertSame(\str_pad($expected, self::LINE_LENGTH + \strlen($expected) - \mb_strlen($expected)) . "\n", $this->output->fetch());
    }

    public function testLinesAreDecoratedWithTheirStyle(): void
    {
        $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, true, new SerendipityHQOutputFormatter(true));
        $style  = new SerendipityHQStyle($this->input, $output);

        $style->errorLine('The message');

        self::assertStringContainsString("\033[", $output->fetch());
    }

    public function testTable(): void
    {
        $this->style->table(['Name', 'Value'], [['first', '1'], ['second', '2']]);

        $table = $this->output->fetch();

        self::assertStringContainsString('Name', $table);
        self::assertStringContainsString('second', $table);
        self::assertStringContainsString('2', $table);
    }

    public function testAskReturnsTheDefaultWhenNotInteractive(): void
    {
        self::assertSame('the default', $this->style->ask('What?', 'the default'));
        self::assertNull($this->style->askHidden('Secret?'));
    }

    public function testConfirmReturnsTheDefaultWhenNotInteractive(): void
    {
        self::assertTrue($this->style->confirm('Sure?'));
        self::assertFalse($this->style->confirm('Sure?', false));
    }

    public function testChoiceReturnsTheDefaultWhenNotInteractive(): void
    {
        self::assertSame('green', $this->style->choice('Color?', ['red', 'green'], 'green'));
    }

    public function testProgressBar(): void
    {
        $this->style->progressStart(3);
        $this->style->progressAdvance();
        $this->style->progressAdvance(2);
        $this->style->progressFinish();

        self::assertStringContainsString('3/3', $this->output->fetch());
    }

    public function testProgressAdvanceRequiresAStartedProgressBar(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The ProgressBar is not started.');

        $this->style->progressAdvance();
    }

    public function testNewLine(): void
    {
        $this->style->newLine(3);

        self::assertSame("\n\n\n", $this->output->fetch());
    }

    public function testWriteAndWriteln(): void
    {
        $this->style->write(['one', 'two']);
        $this->style->writeln('three');

        self::assertSame("onetwothree\n", $this->output->fetch());
    }

    public function testGetErrorStyleWritesToTheErrorOutput(): void
    {
        $output = new ConsoleOutput();
        $style  = new SerendipityHQStyle($this->input, $output);

        self::assertNotSame($style, $style->getErrorStyle());
    }
}
