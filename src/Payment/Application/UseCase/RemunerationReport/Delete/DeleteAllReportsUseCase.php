<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Delete;

use App\Payment\Domain\Model\RemunerationReport;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteAllReportsUseCase implements DeleteAllReportsUseCasePort
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(): DeleteAllReportsResult
    {
        $this->entityManager->createQuery('DELETE FROM ' . RemunerationReport::class)->execute();

        return DeleteAllReportsResult::success();
    }
}
