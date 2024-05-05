<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Exception\DepartmentBonusRuleNotFoundException;
use App\Payment\Domain\Model\DepartmentBonusRule;
use App\Payment\Domain\Repository\DepartmentBonusRuleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineDepartmentBonusRuleRepository implements DepartmentBonusRuleRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findByDepartmentId(int $departmentId): DepartmentBonusRule
    {
        return $this->entityManager->getRepository(DepartmentBonusRule::class)->findOneBy(['departmentId' => $departmentId])
            ?? throw new DepartmentBonusRuleNotFoundException();
    }

    public function save(DepartmentBonusRule $departmentBonusRule): void
    {
        $this->entityManager->persist($departmentBonusRule);
        $this->entityManager->flush();
    }
}
