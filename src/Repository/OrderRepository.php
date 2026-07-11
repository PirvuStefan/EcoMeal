<?php

namespace App\Repository;

use App\Entity\Business;
use App\Entity\Consumer;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[]
     */
    public function findAllDetailed(): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.package', 'p')
            ->addSelect('p')
            ->leftJoin('p.business', 'b')
            ->addSelect('b')
            ->leftJoin('o.consumer', 'c')
            ->addSelect('c')
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Order[]
     */
    public function findByConsumer(Consumer $consumer): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.package', 'p')
            ->addSelect('p')
            ->leftJoin('p.business', 'b')
            ->addSelect('b')
            ->leftJoin('o.consumer', 'c')
            ->addSelect('c')
            ->andWhere('o.consumer = :consumer')
            ->setParameter('consumer', $consumer)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Order[]
     */
    public function findByBusiness(Business $business): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.package', 'p')
            ->addSelect('p')
            ->leftJoin('p.business', 'b')
            ->addSelect('b')
            ->leftJoin('o.consumer', 'c')
            ->addSelect('c')
            ->andWhere('p.business = :business')
            ->setParameter('business', $business)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
