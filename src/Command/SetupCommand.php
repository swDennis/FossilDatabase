<?php

namespace App\Command;

use App\Setup\CreateDatabase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-database',
    description: 'Creates only the Fossil Database and requires the migrations after',
    hidden: false,
    aliases: ['app:install-database']
)]
class SetupCommand extends Command
{
    protected static $defaultName = 'app:create-database';

    protected static $defaultDescription = 'Creates only the Fossil Database and requires the migrations after';

    private CreateDatabase $createDatabase;

    public function __construct(CreateDatabase $createDatabase)
    {
        $this->createDatabase = $createDatabase;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $databaseUserName = $io->ask('Please enter the database user name', 'root');

        $databasePassword = $io->askHidden('Please enter the database password');

        $databaseHostName = $io->ask('Please enter the database host name', 'mysql');

        $databasePort = $io->ask('Please enter the database port', '3306');

        $databaseCreationResult = $this->createDatabase->createDatabase($databaseUserName, $databasePassword, $databaseHostName, $databasePort);
        $io->info($databaseCreationResult);

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
