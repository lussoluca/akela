<?php

namespace App\Tests\Integration;

use App\Core\Domain\Service\GroupImporterService;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GroupDataImporterTest extends KernelTestCase
{

    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    /** @test */
    public function withCorrectDataAGroupIsCreated()
    {
        $data = [
            ['Id', 'Nome gruppo'],
            [1, 'Group test'],
        ];
        $groupRepository = new GroupRepository($this->entityManager);
        $groupImporterService = new GroupImporterService($groupRepository);
        $updatedData = $groupImporterService->processGroups($data);
        dd($updatedData);

        //$group = $groupRepository->find($id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
