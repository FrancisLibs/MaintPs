<?php

namespace App\Repository;

use App\Entity\Order;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order Returns an unique order object
     */
    public function findLastOrder()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * 
     * @return Order[] Returns an array of in progress orders objects (status : en cours)
     */
    public function inProgressOrder()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.status = :val')
            ->setParameter('val', Order::EN_COURS)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSearch(SearchData $search)
    {
        $query = $this
            ->createQueryBuilder('o');

        if(!empty($search->numero)) {
            $query = $query
                ->andWhere('o.orderNumber = :numero')
                ->setParameter('numero', $search->numero)
            ;
        }
            
        return $query->getQuery()->getResult();
    }

}
