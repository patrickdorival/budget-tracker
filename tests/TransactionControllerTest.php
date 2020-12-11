<?php

namespace App\Tests;

use App\Entity\Transaction;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    private $entityManager;
    private KernelBrowser $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        self::bootKernel();

        $container = self::$container;

        $this->entityManager = $container->get('doctrine')->getManager();
    }


    /** @test */
    public function canReturnTransactions()
    {
        $transaction = new Transaction();
        $transaction->setAmount('100')
            ->setDescription('Test Description')
            ->setCreatedOn(Carbon::now());

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        $this->client->request('GET', '/transactions');

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());
        $this->assertCount(1, json_decode($response->getContent())->data);

    }
}
