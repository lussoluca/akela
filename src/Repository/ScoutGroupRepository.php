<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ScoutGroupRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<Group>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Group::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    /** @return Group[]  */
    public function all(): array
    {
        return $this->objectRepository->findBy([], ['name' => 'ASC']);
    }

    public function findOrFail(string $id, bool $allowDeleted = false): Group
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        $user = $this->objectRepository->find($id);

        if (null == $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function find(string $id, bool $allowDeleted = false): ?Group
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }

    /** @return Group[]  */
    public function findAll(bool $allowDeleted = false): array
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->findAll();
    }
}
