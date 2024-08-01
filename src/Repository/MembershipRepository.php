<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Membership;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\MembershipNotFoundException;

class MembershipRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository<Membership>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Membership::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    public function all(): array
    {
        return $this->objectRepository->findBy([], ['name' => 'ASC']);
    }

    public function findOrFail(string $id, bool $allowDeleted = false): Membership
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        $user = $this->objectRepository->find($id);

        if (null == $user) {
            throw new MembershipNotFoundException();
        }

        return $user;
    }

    public function find(string $id, bool $allowDeleted = false): ?Membership
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }
}
