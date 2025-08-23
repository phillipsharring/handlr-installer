<?php

declare(strict_types=1);

namespace HandlrInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    public function getName(): string{
        return 'new';
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new Handlr app')
            ->addArgument('path', InputArgument::REQUIRED, 'App directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        if (!is_dir($path)) {
            $output->writeln("<info>✔ Created App Directory in $path</info>");
            mkdir($path, 777, true);
        }

        if ($this->existOnDebug()) {
            $output->writeln("<comment>✔ Debug mode is on, skipping git clone</comment>");
            return Command::SUCCESS;
        }

        passthru("git clone https://github.com/phillipsharring/handlr-app \"$path\"");

        chdir($path);
        passthru("rm -rf .git && composer install");

        $output->writeln("<info>✔ New Handlr app created in $path</info>");
        return Command::SUCCESS;
    }

    private function existOnDebug(): bool
    {
        return defined('HANDLR_INSTALLER_DEBUG');
    }
}
