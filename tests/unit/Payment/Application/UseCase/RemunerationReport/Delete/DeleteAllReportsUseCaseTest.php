<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Application\UseCase\RemunerationReport\Delete;

use App\Payment\Application\UseCase\RemunerationReport\Delete\DeleteAllReportsUseCase;
use App\Payment\Domain\Model\RemunerationReport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DeleteAllReportsUseCaseTest extends TestCase
{
    private readonly EntityManagerInterface|MockObject $entityManager;

    private readonly DeleteAllReportsUseCase $deleteAllReportsUseCase;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->deleteAllReportsUseCase = new DeleteAllReportsUseCase(
            $this->entityManager
        );
    }

    public function testDeletionAll(): void
    {
        $query = $this->createMock(Query::class);

        $query->expects($this->once())
            ->method('execute');
        $query->expects($this->once())
            ->method('execute');

        $this->entityManager->expects($this->once())
            ->method('createQuery')
            ->with('DELETE FROM ' . RemunerationReport::class)
            ->willReturn($query);

        $this->deleteAllReportsUseCase->execute();
    }

}
