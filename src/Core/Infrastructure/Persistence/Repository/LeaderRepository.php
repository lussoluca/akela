<?php

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Model\Leader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Leader>
 */
class LeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leader::class);
    }
}
