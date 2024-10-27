<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Model\Leader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LeaderRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<Leader>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Leader::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    public function find(string $id, bool $allowDeleted = false): ?Leader
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }

    public function add(Leader $leader): void
    {
        $this->entityManager->persist($leader);
        $this->entityManager->flush();
    }

    public function delete(Leader $leader): void
    {
        $this->entityManager->remove($leader);
        $this->entityManager->flush();
    }
}
