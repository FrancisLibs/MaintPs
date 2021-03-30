<?php

namespace App\Repository;

use App\Entity\Order;
use App\Data\SearchOrder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Cache\VoidCache;

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
    /**
     * Récupère les commandes liées à une recherche
     *
     * @param SearchData $search
     * @return Order[]
     */
    public function findSearch(SearchOrder $search): Array
    {
        $query = $this
            ->createQueryBuilder('o')
            ->select('o', 'p', 'u', 'a')
            ->join('o.provider', 'p')
            ->join('o.user', 'u')
            ->join('o.account', 'a');

        if(!empty($search->numero)) {
            $query = $query
                ->andWhere('o.orderNumber = :numero')
                ->setParameter('numero', $search->numero);
        }
        
        if(!empty($search->account)) {
            $query = $query
            ->andWhere('a.id IN (:numero)')
            ->setParameter('numero', $search->account);
        }

        return $query->getQuery()->getResult();
    }

}
