<?php

namespace App\Tests\Integration;

use App\Core\Domain\Service\GroupImporterService;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use Doctrine\ORM\EntityManager;
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
          [
            '6622f234-eef6-4bc4-a21a-8d9e95049d68',
            'Alessandria 2',
            'O0931',
            'IT20M0503410403000000000014',
            'Piazza Giovanni XXIII',
            'Alessandria',
            '15121',
            'Piemonte',
          ],
        ];
        $groupRepository = new GroupRepository($this->entityManager);
        $groupImporterService = new GroupImporterService($groupRepository);
        $groupImporterService->processGroups($data);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
