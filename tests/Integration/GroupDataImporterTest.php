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

        $group = $groupRepository->find('6622f234-eef6-4bc4-a21a-8d9e95049d68');

        $this->assertEquals('Alessandria 2', $group->getName());
        $this->assertEquals('O0931', $group->getCodiceOrdinale());
        $this->assertEquals('IT20M0503410403000000000014', $group->getIban());
        $this->assertEquals('Piazza Giovanni XXIII', $group->getAddress()->getAddressLine1());
        $this->assertEquals('Alessandria', $group->getAddress()->getLocality());
        $this->assertEquals('15121', $group->getAddress()->getPostalCode());
        $this->assertEquals('Piemonte', $group->getAddress()->getAdministrativeArea());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

}
