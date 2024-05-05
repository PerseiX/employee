<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Generate;

interface GenerateSalaryReportUseCasePort
{
    public function execute(GenerateSalaryReportCommand $command): GenerateSalaryReportResult;
}
