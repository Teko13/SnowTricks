<?php

namespace App\Repository;

use App\Entity\TrickFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrickFile>
 *
 * @method TrickFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickFile[]    findAll()
 * @method TrickFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickFile::class);
    }

//    /**
//     * @return TrickFile[] Returns an array of TrickFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrickFile
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
