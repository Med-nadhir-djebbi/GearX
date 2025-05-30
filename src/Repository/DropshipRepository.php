<?php

namespace App\Repository;

use App\Entity\Dropship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dropship>
 *
 * @method Dropship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dropship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dropship[]    findAll()
 * @method Dropship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DropshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dropship::class);
    }

    public function save(Dropship $entity, bool $flush = false): void
    {
        $entity->setUpdatedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Dropship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Dropship[] Returns an array of active Dropship entities
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.status = :status')
            ->setParameter('status', 'active')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
} 