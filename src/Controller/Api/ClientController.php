<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\CreateClient;
use App\DTO\UpdateClient;
use App\Entity\Client;
use App\Manager\ClientManager;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route(path: '/clients')]
class ClientController extends AbstractController
{
    // get all
    #[Route('/', methods: ['GET'], format: 'json')]
    public function list(ClientRepository $repository, SerializerInterface $serializer, #[MapQueryParameter] int $page = 1): Response
    {
        return new Response(
            $serializer->serialize($repository->findPage($page),'json',
                [
                    'groups' => ['client_list']
                ])
        );
    }

    // get one
    #[Route('/{id}', methods: ['GET'], format: 'json')]
    public function get(Client $client, SerializerInterface $serializer, CacheItemPoolInterface $cache): Response
    {
        if ($client) {
            $clientItem = $cache->getItem('client.search' . $client->getFirstName());

            if (!$clientItem->isHit()) {
                $clientItem->set($client);
                $clientItem->expiresAt(new \DateTime('+1 hour'));
                $cache->save($clientItem);
            }
            return new Response(
                $serializer->serialize($client, 'json',
                    [
                        'groups' => ['client_item', 'order_item', 'car_item']
                    ]
                )
            );


        }

        return new Response();


    }


    // create
    #[Route('', methods: ['POST'], format: 'json')]
    public function create(#[MapRequestPayload] CreateClient $createClient, ClientManager $clientManager): JsonResponse
    {
        return new JsonResponse($clientManager->createClient($createClient), Response::HTTP_CREATED);
    }


    //update
    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function update(Client $client, #[MapRequestPayload] UpdateClient $updateClient,
                           ClientManager $clientManager, SerializerInterface $serializer): Response
    {
        return new Response(
            $serializer->serialize($clientManager->updateClient($updateClient,$client), 'json',
                [
                    'groups' => ['client_update']
                ]
            )
        );

    }


    //delete
    #[Route('/{id}', methods: ['DELETE'], format: 'json')]
    public function delete(Client $client, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->remove($client);
            $entityManager->flush();

        } catch (ORMException $exception) {
            $logger->error($exception->getMessage());
            return new Response('Error on deleting client');
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

}