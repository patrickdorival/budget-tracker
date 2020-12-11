<?php

namespace App\Controller;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transactions", name="transaction", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $transactionRepository = $em->getRepository(Transaction::class);

        $transactions = $transactionRepository->findAll();
        $json = $serializer->serialize($transactions, 'json', ['groups' => 'transaction:list']);

        return new JsonResponse($json, 200, ['Access-Control-Allow-Origin' => '*'], true);
    }
}
