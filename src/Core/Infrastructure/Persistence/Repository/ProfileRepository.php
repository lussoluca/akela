<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\Repository;

use App\Core\Domain\Model\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProfileRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository<Profile>
     */
    private EntityRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $repository = $this->entityManager->getRepository(Profile::class);
        assert($repository instanceof EntityRepository);
        $this->objectRepository = $repository;
    }

    /**
     * @return \App\Core\Domain\Model\Profile[]
     */
    public function all(): array
    {
        return $this->objectRepository->findAll();
    }

    public function find(string $id, bool $allowDeleted = false): ?Profile
    {
        if ($allowDeleted && $this->entityManager->getFilters()->isEnabled('softdeleteable')) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }

        return $this->objectRepository->find($id);
    }

    public function add(Profile $profile): void
    {
        $this->entityManager->persist($profile);
        $this->entityManager->flush();
    }

    public function delete(Profile $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function count(): int
    {
        return intval($this->objectRepository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult());
    }
}
