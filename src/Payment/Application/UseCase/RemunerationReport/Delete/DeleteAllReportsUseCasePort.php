<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Delete;

interface  DeleteAllReportsUseCasePort
{
    public function execute(): DeleteAllReportsResult;
}
