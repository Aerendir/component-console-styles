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

namespace SerendipityHQ\Bundle\ConsoleStyles\Tests\Command;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use SerendipityHQ\Bundle\ConsoleStyles\Command\SerendipityHQStylesCommand;
use SerendipityHQ\Bundle\ConsoleStyles\Command\SymfonyStylesCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

#[IgnoreDeprecations]
final class StylesCommandsTest extends TestCase
{
    public function testSerendipityHQStylesCommandIsConfigured(): void
    {
        $command = new SerendipityHQStylesCommand();

        self::assertSame('console:styles:serendipityhq', $command->getName());
        self::assertSame("A command to display all available SerendipityHQ's console style.", $command->getDescription());
        self::assertTrue($command->getDefinition()->hasOption('show-ansi'));
    }

    public function testSymfonyStylesCommandIsConfigured(): void
    {
        $command = new SymfonyStylesCommand();

        self::assertSame('console:styles:symfony', $command->getName());
        self::assertSame("A command to display all available Symfony's console style.", $command->getDescription());
        self::assertTrue($command->getDefinition()->hasOption('show-ansi'));
    }

    public function testSerendipityHQStylesCommandDisplaysTheStyles(): void
    {
        $tester = new CommandTester(new SerendipityHQStylesCommand());

        self::assertSame(Command::SUCCESS, $tester->execute(['--show-ansi' => true]));

        $display = $tester->getDisplay();
        self::assertStringContainsString('Serendipity HQ Style Guide', $display);
        self::assertStringContainsString('Those are the available ANSI colors', $display);
        self::assertStringContainsString('This is an info block (this is provided by SerendipityHQStyle).', $display);
        self::assertStringContainsString('This is a warning line without background.', $display);
    }

    public function testSymfonyStylesCommandDisplaysTheStyles(): void
    {
        $tester = new CommandTester(new SymfonyStylesCommand());

        self::assertSame(Command::SUCCESS, $tester->execute([]));

        $display = $tester->getDisplay();
        self::assertStringNotContainsString('Those are the available ANSI colors', $display);
        self::assertStringContainsString('This is a warning block', $display);
    }
}
