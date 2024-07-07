<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    private const CLIENT_PAGE = 5;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findPage(int $page = 1)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id')
            ->getQuery()
            ->setFirstResult(self::CLIENT_PAGE * $page - self::CLIENT_PAGE)
            ->setMaxResults(self::CLIENT_PAGE)
            ->getResult();

    }




}