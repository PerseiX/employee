<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\RemunerationReport\Generate;


use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Exception\DepartmentBonusRuleNotFoundException;
use App\Payment\Domain\Exception\DepartmentNotFoundException;
use App\Payment\Domain\Factory\BonusCalculationPolicyFactoryInterface;
use App\Payment\Domain\Model\Employee;
use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Domain\Repository\DepartmentBonusRuleRepositoryInterface;
use App\Payment\Domain\Repository\DepartmentRepositoryInterface;
use App\Payment\Domain\Repository\EmployeeRepositoryInterface;
use App\Payment\Domain\Repository\RemunerationReportRepositoryInterface;
use App\Payment\Domain\System\DateTimeProviderInterface;

final class GenerateSalaryReportUseCase implements GenerateSalaryReportUseCasePort
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly DepartmentBonusRuleRepositoryInterface $departmentBonusRuleRepository,
        private readonly RemunerationReportRepositoryInterface $remunerationReportRepository,
        private readonly BonusCalculationPolicyFactoryInterface $bonusCalculationPolicyFactory,
        private readonly DateTimeProviderInterface $dateTimeProvider,
    ) {
    }

    public function execute(GenerateSalaryReportCommand $command): GenerateSalaryReportResult
    {
        $allEmployees = $this->employeeRepository->findAll();
        $remunerations = [];

        /** @var Employee $employee */
        foreach ($allEmployees as $employee) {
            try {
                $department = $this->departmentRepository->findOneById($employee->getDepartmentId());
                $bonusRule = $this->departmentBonusRuleRepository->findByDepartmentId($department->getId());

                $bonusCalculationPolicy = $this->bonusCalculationPolicyFactory->create($bonusRule);

                $salaryWithBonus = $employee->calculateSalary($bonusCalculationPolicy, $this->dateTimeProvider);
            } catch (DepartmentNotFoundException $exception) {
                return GenerateSalaryReportResult::departmentNotFound();
            }catch (DepartmentBonusRuleNotFoundException $exception) {
                return GenerateSalaryReportResult::departmentBonusRuleNotFound();
            }catch (BonusCalculationPolicyCanNotBeCreatedException $exception) {
                return GenerateSalaryReportResult::bonusCalculatorCanNotBeCreated();
            }

            $remunerations[] = new RemunerationReport(
                $employee->getId(),
                $employee->getName(),
                $employee->getSurname(),
                $department->getName(),
                $employee->getBaseSalary(),
                $salaryWithBonus - $employee->getBaseSalary(),
                $bonusRule->bonusType,
                $salaryWithBonus
            );
        }

        $this->remunerationReportRepository->save(...$remunerations);

        return GenerateSalaryReportResult::success();
    }
}
