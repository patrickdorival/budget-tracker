<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionCategoryAssignmentController extends AbstractController
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    /**
     * TransactionController constructor.
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/transaction-categories/{id}/assign", name="transaction_categories#assign", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent());

        $transactionCategoryRepository = $this->em->getRepository(TransactionCategory::class);
        $transactionRepository = $this->em->getRepository(Transaction::class);


        /** @var Transaction $transaction */
        $transaction = $transactionRepository->find($payload->transactionId);

        /** @var TransactionCategory $category */
        $category = $transactionCategoryRepository->find($request->get('id'));

        if ($category && $transaction) {
            $category->addTransaction($transaction);
            $transaction->setTransactionCategory($category);
            $this->em->flush();

            $json = $this->serializer->serialize($transaction, 'json', ['groups' => 'transaction:list']);

            return new JsonResponse($json, 200, [], true);
        }
        else
        {
            return new JsonResponse(['success' => false], 400);
        }


    }

    public function delete(): Response
    {
        return new Response(null, 204);
    }

}
