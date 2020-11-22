<?php

namespace App\Command;

use App\Service\BankStatement\Finder;
use App\Service\BankStatement\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BudgetParseCsvCommand extends Command
{
    protected static $defaultName = 'budget:parse-csv';
    /**
     * @var Finder
     */
    private $bankStatementFinder;
    /**
     * @var Parser
     */
    private $parser;

    /**
     * BudgetParseCsvCommand constructor.
     */
    public function __construct(Finder $bankStatementFinder, Parser $parser)
    {
        $this->bankStatementFinder = $bankStatementFinder;

        parent::__construct();
        $this->parser = $parser;
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->title('Bank Statement Parser');

        $io->section('Find all bank statements to parse...');

        $files = $this->bankStatementFinder->getCsvFiles();

        $io->text($files);

        $io->section('Attempting to parse bank statements now...');

        array_map(function($f) use ($io) {
            $dataArray = $this->parser->parseFile($f);
            $io->table(Parser::HEADERS, $dataArray);
        }, $files);

        return Command::SUCCESS;
    }
}
