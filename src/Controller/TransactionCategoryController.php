<?php

namespace App\Controller;

use App\Entity\TransactionCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionCategoryController extends AbstractController
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
     * @Route("/transaction-categories", name="transaction_categories#index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $transactionCategoryRepository = $this->em->getRepository(TransactionCategory::class);

        $categories = $transactionCategoryRepository->findAll();
        $json = $this->serializer->serialize($categories, 'json', ['groups' => 'category:list']);

        return new JsonResponse($json, 200, ['Access-Control-Allow-Origin' => '*'], true);
    }

    /**
     * @Route("/transaction-categories", name="transaction_categories#create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $payload = json_decode($request->getContent());

        $category = new TransactionCategory();
        $category->setName($payload->name);

        $this->em->persist($category);
        $this->em->flush();

        $json = $this->serializer->serialize($category, 'json');


        return new JsonResponse($json, 201);

    }

    /**
     * @Route("/transaction-categories/{id}", name="transaction_categories#destroy", methods={"DELETE"})
     */
    public function delete(Request $request)
    {
        $transactionCategoryRepository = $this->em->getRepository(TransactionCategory::class);

        $transaction = $transactionCategoryRepository->find($request->get('id'));

        $this->em->remove($transaction);
        $this->em->flush();

        return new Response(null, 204);
    }

}
