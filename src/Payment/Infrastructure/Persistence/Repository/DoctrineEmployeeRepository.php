<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence\Repository;

use App\Payment\Domain\Model\Employee;
use App\Payment\Domain\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineEmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Employee::class)->findAll();
    }

    public function save(Employee $employee): void
    {
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }
}
