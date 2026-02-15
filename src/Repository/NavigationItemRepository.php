<?php

namespace App\Repository;

use App\Entity\NavigationItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NavigationItem>
 */
class NavigationItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavigationItem::class);
    }

    /**
     * @return NavigationItem[]
     */
    public function findVisibleOrdered(): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.isVisible = :visible')
            ->setParameter('visible', true)
            ->andWhere('n.parent IS NULL')
            ->orderBy('n.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return NavigationItem[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
