<?php

namespace App\Command;

use App\Entity\Transaction;
use App\Service\BankStatement\Finder;
use App\Service\BankStatement\Parser;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * BudgetParseCsvCommand constructor.
     */
    public function __construct(Finder $bankStatementFinder, Parser $parser, EntityManagerInterface $em)
    {
        $this->bankStatementFinder = $bankStatementFinder;
        $this->parser = $parser;
        $this->em = $em;

        parent::__construct();
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

        $io->title('Bank Statement Parser');

        $io->section('Find all bank statements to parse...');

        $files = $this->bankStatementFinder->getCsvFiles();

        $io->text($files);

        $io->section('Attempting to parse bank statements now...');


        array_map(function($f) use ($io) {
            $dataArray = $this->parser->parseFile($f);
            $filename = basename($f);
            $io->text("Processing file: {$filename}...");

            $io->progressStart();

            array_map(function($data) use ($io){
                $transaction = new Transaction();
                $transaction->setCreatedOn(Carbon::createFromFormat( 'd/m/Y', trim($data[0])))
                            ->setAmount($data[1] * 100)
                            ->setDescription($data[2]);

                $this->em->persist($transaction);
                $io->progressAdvance();
            }, $dataArray);

            $io->progressFinish();
            $io->text("Finished processing file: {$filename}...");
            $io->newLine();
        }, $files);

        $this->em->flush();
        $io->text("Transactions flushed...");

        return Command::SUCCESS;
    }
}
