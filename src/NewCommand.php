<?php

declare(strict_types=1);

namespace HandlrInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    protected static $defaultName = 'new';

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new Handlr app')
            ->addArgument('path', InputArgument::REQUIRED, 'App directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        passthru("git clone https://github.com/phillipsharring/handlr-app \"$path\"");
        mkdir($path, 777, true);
        chdir($path);
        passthru("rm -rf .git && composer install");

        $output->writeln("<info>✔ New Handlr app created in $path</info>");
        return Command::SUCCESS;
    }
}
