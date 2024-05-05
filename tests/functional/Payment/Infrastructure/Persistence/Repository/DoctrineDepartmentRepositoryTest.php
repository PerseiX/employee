<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\Department;
use App\Payment\Infrastructure\Persistence\Repository\DoctrineDepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineDepartmentRepositoryTest extends KernelTestCase
{
    private readonly DoctrineDepartmentRepository $departmentRepository;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->departmentRepository = $container->get(DoctrineDepartmentRepository::class);

        $this->cleanUpDatabase();
    }

    public function testSuccess(): void
    {
        $this->departmentRepository->save(new Department(1, 'HR'));
        $this->departmentRepository->save($department2 = new Department(2, 'ID'));

        self::assertEquals(
            $department2,
            $this->departmentRepository->findOneById(2)
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
    }

    private function cleanUpDatabase(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . Department::class)->execute();

        $this->entityManager->getConnection()
            ->prepare('ALTER TABLE departments AUTO_INCREMENT = 1')
            ->executeStatement();
    }
}
