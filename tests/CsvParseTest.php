<?php

namespace App\Tests;

use App\Entity\Transaction;
use App\Service\BankStatement\Finder;
use App\Service\BankStatement\Parser;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CsvParseTest extends WebTestCase
{
    private $finder;
    private $parser;


    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        self::bootKernel();

        $container = self::$container;

        $this->finder = $container->get(Finder::class);
        $this->parser = $container->get(Parser::class);
        $this->entityManager = $container->get('doctrine')->getManager();
    }

    /** @test */
    public function canRetriveFiles()
    {
        $files = $this->finder->getCsvFiles();
        $this->assertCount(3, $files);
    }

    /** @test */
    public function canParseACsvFile()
    {
        $files = $this->finder->getCsvFiles();
        $dataArray = $this->parser->parseFile($files[0]);
        $this->assertCount(53, $dataArray);
    }

    /** @test */
    public function parsesCsvAndLoadsRecords()
    {
        $files = $this->finder->getCsvFiles();

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $transactions = $transactionRepository->findAll();

        array_map(function($f) {
            $dataArray = $this->parser->parseFile($f);

            array_map(function($data) {
                $transaction = new Transaction();
                $transaction->setCreatedOn(Carbon::createFromFormat( 'd/m/Y', trim($data[0])))
                    ->setAmount($data[1])
                    ->setDescription($data[2]);

                $this->entityManager->persist($transaction);
            }, $dataArray);
        }, $files);

        $this->entityManager->flush();

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $transactions = $transactionRepository->findAll();

        $this->assertCount(228, $transactions);
    }

}
