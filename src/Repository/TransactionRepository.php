<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findByOrder(Order $order): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.orderRef = :order')
            ->setParameter('order', $order)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findSuccessfulTransactionByOrder(Order $order): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.orderRef = :order')
            ->andWhere('t.status = :status')
            ->setParameter('order', $order)
            ->setParameter('status', Transaction::STATUS_COMPLETED)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
} 