<?php

namespace App\Medical\Infrastructure\Persistence\Repository;

use App\Medical\Domain\Model\MedicalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicalData>
 */
class MedicalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalData::class);
    }
}
