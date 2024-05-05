<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Exception\DepartmentNotFoundException;
use App\Payment\Domain\Model\Department;
use App\Payment\Domain\Repository\DepartmentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineDepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Department $department): void
    {
        $this->entityManager->persist($department);
        $this->entityManager->flush();
    }

    public function findOneById(int $id): Department
    {
        return $this->entityManager->find(Department::class, $id)
            ?? throw new DepartmentNotFoundException();
    }
}
