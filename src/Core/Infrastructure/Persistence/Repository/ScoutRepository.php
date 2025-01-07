<?php

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Exception\ScoutNotFoundException;
use App\Core\Domain\Model\Scout;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ScoutRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<Scout>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Scout::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    /** @return array<int, Scout> */
    public function all(): array
    {
        return $this->objectRepository->findBy([], ['name' => 'ASC']);
    }

    public function findOrFail(string $id, bool $allowDeleted = false): Scout
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        $user = $this->objectRepository->find($id);

        if (null == $user) {
            throw new ScoutNotFoundException($id);
        }

        return $user;
    }

    public function find(string $id, bool $allowDeleted = false): ?Scout
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }

    public function add(Scout $scout): void
    {
        $this->entityManager->persist($scout);
        $this->entityManager->flush();
    }
}
