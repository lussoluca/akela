<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Exception\UnitNotFoundException;
use App\Core\Domain\Model\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UnitRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<Unit>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Unit::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    /** @return Unit[] */
    public function all(): array
    {
        return $this->objectRepository->findBy([], ['name' => 'ASC']);
    }

    public function findOrFail(string $id, bool $allowDeleted = false): Unit
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        $user = $this->objectRepository->find($id);

        if (null == $user) {
            throw new UnitNotFoundException();
        }

        return $user;
    }

    public function find(string $id, bool $allowDeleted = false): ?Unit
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }
}
