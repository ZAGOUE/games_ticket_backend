<?php

namespace App\Repository;

use App\Entity\TicketOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TicketOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketOrder::class);
    }
    public function countOrdersGroupedByOffer(): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.offer', 'offerEntity')
            ->select('offerEntity.name AS offer', 'COUNT(o.id) AS total')
            ->groupBy('offerEntity.name')
            ->getQuery()
            ->getArrayResult();
    }

    public function findPaidOrdersByUser(int $userId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :userId')
            ->andWhere('o.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'PAID')
            ->getQuery()
            ->getResult();
    }


}
