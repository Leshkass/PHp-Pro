<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CarRepository extends ServiceEntityRepository
{
    private const CAR_PAGE = 5;
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
    public function findPage(int $page = 1)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id')
            ->getQuery()
            ->enableResultCache(600)
            ->setFirstResult(self::CAR_PAGE * $page - self::CAR_PAGE)
            ->setMaxResults(self::CAR_PAGE)
            ->getResult();

    }

    /**
     * @return Car[]
     */
    public function findAllByBrand(string $brand): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.brand = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }

}