<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * @param string $color
     * @return array|Car[]
     */
    public function findAllByColor(string $color): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.color = :color')
            ->setParameter('color', $color)
            ->getQuery()
            ->getResult();
    }


}