<?php
declare(strict_types=1);

namespace App\Manager;

use App\DTO\CreateClient;
use App\DTO\UpdateClient;
use App\Entity\Client;
use App\Repository\CarRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ClientManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarRepository          $carRepository,
        private readonly OrderRepository        $orderRepository)
    {

    }


    public function createClient(CreateClient $createClient): Client
    {
        $car = $this->carRepository->find($createClient->carId);

        if (!$car) {
            throw new EntityNotFoundException('Car is not found');
        }
        $order = $this->orderRepository->find($createClient->orderId);

        if (!$order) {
            throw new EntityNotFoundException('Order is not found');
        }

        $client = new Client();
        $client->setFirstName($createClient->firstName);
        $client->setLastName($createClient->lastName);
        $client->setEmail($createClient->email);
        $client->addOrder($order);
        $client->addCar($car);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;

    }

    public function updateClient(UpdateClient $updateClient, Client $client): Client
    {
        if ($updateClient->firstName !== null) {
            $client->setFirstName($updateClient->firstName);
        }

        if ($updateClient->lastName !== null) {
            $client->setLastName($updateClient->lastName);
        }

        if ($updateClient->email !== null) {
            $client->setEmail($updateClient->email);
        }

        $this->entityManager->flush();

        return $client;

    }

}