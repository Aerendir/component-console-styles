<?php

/*
 * This file is part of the Serendipity HQ Console Styles Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Bundle\ConsoleStyles\Command;

use SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter\SerendipityHQOutputFormatter;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Style\SerendipityHQStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Displays the custom console style implemented by SerendipityHQ.
 */
final class SerendipityHQStylesCommand extends Command
{
    /** @var string $defaultName */
    protected static $defaultName = 'console:styles:serendipityhq';

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription("A command to display all available SerendipityHQ's console style.")
            ->addOption('show-ansi', null, InputOption::VALUE_NONE, 'Shows the ANSI colors.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputFormatter = new SerendipityHQOutputFormatter(true);
        $ioWriter        = new SerendipityHQStyle($input, $output);
        $ioWriter->setFormatter($outputFormatter);

        $ioWriter->title('Serendipity HQ Style Guide');

        if ($input->getOption('show-ansi')) {
            // Print out the available ANSI colors
            $ioWriter->section('Those are the available ANSI colors');
            $ioWriter->block('black', null, 'fg=black');
            $ioWriter->block('red', null, 'fg=red');
            $ioWriter->block('green', null, 'fg=green');
            $ioWriter->block('yellow', null, 'fg=yellow');
            $ioWriter->block('blue', null, 'fg=blue');
            $ioWriter->block('magenta', null, 'fg=magenta');
            $ioWriter->block('cyan', null, 'fg=cyan');
            $ioWriter->block('white', null, 'fg=white');
            $ioWriter->block('default', null, 'fg=default');
        }

        // Print out the <styles> defined in OutputFormatter
        $ioWriter->section('The \<styles> defined in OutputFormatter');
        $ioWriter->writeln('<caution>This is a < caution >.</caution>');
        $ioWriter->writeln('<comment>This is a < comment >.</comment>');
        $ioWriter->writeln('<error>This is an < error >.</error>');
        $ioWriter->writeln('<info>This is an < info >.</info>');
        $ioWriter->writeln('<note>This is a < note >.</note>');
        $ioWriter->writeln('<success>This is a < success >.</success>');
        $ioWriter->writeln('<warning>This is a < warning >.</warning>');
        $ioWriter->writeln('<question>This is a < question >.</question>');

        $ioWriter->writeln('<caution-nobg>This is a < caution-nobg >.</caution-nobg>');
        $ioWriter->writeln('<comment-nobg>This is a < comment-nobg >.</comment-nobg>');
        $ioWriter->writeln('<error-nobg>This is an < error-nobg >.</error-nobg>');
        $ioWriter->writeln('<info-nobg>This is an < info-nobg >.</info-nobg>');
        $ioWriter->writeln('<note-nobg>This is a < note-nobg >.</note-nobg>');
        $ioWriter->writeln('<success-nobg>This is a < success-nobg >.</success-nobg>');
        $ioWriter->writeln('<warning-nobg>This is a < warning-nobg >.</warning-nobg>');
        $ioWriter->writeln('<question-nobg>This is a < question-nobg >.</question-nobg>');

        // Print out the shortcut methods of SymfonyStyle
        $ioWriter->section('The shortcuts methods of SymfonyStyle (They draw a block)');
        $ioWriter->caution('This is a caution block.');
        $ioWriter->comment('This is a comment block.');
        $ioWriter->error('This is an error block.');
        $ioWriter->info('This is an info block (this is provided by SerendipityHQStyle).');
        $ioWriter->note('This is a note block.');
        $ioWriter->success('This is a success block.');
        $ioWriter->warning('This is a warning block.');

        // Print out the shortcut methods of SerendipityHQStyle
        $ioWriter->section('The shortcuts methods of SerendipityHQStyle (They draw a line)');
        $ioWriter->cautionLine('This is a caution line.');
        $ioWriter->cautionLineNoBg('This is a caution line without background.');
        $ioWriter->commentLine('This is a comment line.');
        $ioWriter->commentLineNoBg('This is a comment line without background.');
        $ioWriter->errorLine('This is an error line.');
        $ioWriter->errorLineNoBg('This is an error line without background.');
        $ioWriter->infoLine('This is an info line.');
        $ioWriter->infoLineNoBg('This is an info line without background.');
        $ioWriter->noteLine('This is a note line.');
        $ioWriter->noteLineNoBg('This is a note line without backgrund.');
        $ioWriter->successLine('This is a success line.');
        $ioWriter->successLineNoBg('This is a success line without background.');
        $ioWriter->warningLine('This is a warning line.');
        $ioWriter->warningLineNoBg('This is a warning line without background.');

        return 0;
    }
}
