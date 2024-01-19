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

}
