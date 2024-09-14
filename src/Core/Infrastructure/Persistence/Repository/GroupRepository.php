<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Model\Profile;
use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Model\Group;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class GroupRepository
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

    /** @return Group[] */
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

    /** @return Group[] */
    public function findAll(bool $allowDeleted = false): array
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->findAll();
    }

    public function add(Group $group): void
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }

    public function delete(Group $group): void
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }
}
