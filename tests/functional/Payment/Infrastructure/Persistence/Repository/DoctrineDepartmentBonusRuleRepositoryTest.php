<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Infrastructure\Persistence\Repository\DoctrineDepartmentBonusRuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineDepartmentBonusRuleRepositoryTest extends KernelTestCase
{
    private readonly DoctrineDepartmentBonusRuleRepository $departmentBonusRuleRepository;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->departmentBonusRuleRepository = $container->get(DoctrineDepartmentBonusRuleRepository::class);

        $this->cleanUpDatabase();
    }

    public function testSuccess(): void
    {
        $this->departmentBonusRuleRepository->save(
            $departmentBonusRule = new DepartmentBonusRule(
                1,
                ['class' => 'AwesomeClass', 'test' => 'config1'],
                'percentage'
            )
        );

        self::assertEquals(
            $departmentBonusRule,
            $this->departmentBonusRuleRepository->findByDepartmentId(1)
        );
        self::assertEquals(
            ['class' => 'AwesomeClass', 'test' => 'config1'],
            $this->departmentBonusRuleRepository->findByDepartmentId(1)->configuration
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->cleanUpDatabase();
    }

    private function cleanUpDatabase(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . DepartmentBonusRule::class)->execute();
    }
}
