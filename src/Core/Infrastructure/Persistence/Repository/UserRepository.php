<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(User::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    /** @return User[] */
    public function all(): array
    {
        return $this->objectRepository->findBy([], ['surname' => 'ASC']);
    }

    public function findOrFail(string $id, bool $allowDeleted = false): User
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

    public function find(string $id, bool $allowDeleted = false): ?User
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }
}
