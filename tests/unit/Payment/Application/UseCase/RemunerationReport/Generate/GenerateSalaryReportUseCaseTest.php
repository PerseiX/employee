<?php

declare(strict_types=1);

namespace App\Tests\unit\Payment\Application\UseCase\RemunerationReport\Generate;

use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportCommand;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportResult;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportUseCase;
use App\Payment\Domain\Exception\BonusCalculationPolicyCanNotBeCreatedException;
use App\Payment\Domain\Factory\BonusCalculationPolicyFactory;
use App\Payment\Domain\Factory\BonusCalculationPolicyFactoryInterface;
use App\Payment\Domain\Model\Department;
use App\Payment\Domain\Model\Employee;
use App\Payment\Domain\Model\RemunerationReport;
use App\Payment\Domain\Policy\BonusCalculationPolicy;
use App\Payment\Domain\Repository\DepartmentRepositoryInterface;
use App\Payment\Domain\Repository\EmployeeRepositoryInterface;
use App\Payment\Domain\Repository\RemunerationReportRepositoryInterface;
use App\Payment\Domain\System\DateTimeProviderInterface;
use App\Payment\Infrastructure\Persistence\Repository\InMemoryDepartmentBonusRuleRepository;
use App\Payment\Infrastructure\Persistence\Repository\InMemoryDepartmentRepository;
use App\Payment\Infrastructure\Persistence\Repository\InMemoryEmployeeRepository;
use App\Payment\Infrastructure\Persistence\Repository\InMemoryRemunerationReportRepository;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GenerateSalaryReportUseCaseTest extends TestCase
{
    private readonly DepartmentRepositoryInterface $departmentRepository;
    private readonly EmployeeRepositoryInterface $employeeRepository;
    private readonly RemunerationReportRepositoryInterface $remunerationReportRepository;
    private readonly BonusCalculationPolicyFactory|MockObject $bonusCalculationPolicyFactory;
    private readonly DateTimeProviderInterface|MockObject $dateTimeProvider;
    private readonly GenerateSalaryReportUseCase $reportUseCase;

    protected function setUp(): void
    {
        $this->departmentRepository = new InMemoryDepartmentRepository();
        $this->employeeRepository = new InMemoryEmployeeRepository();
        $departmentBonusRuleRepository = new InMemoryDepartmentBonusRuleRepository();
        $this->remunerationReportRepository = new InMemoryRemunerationReportRepository();
        $this->bonusCalculationPolicyFactory = $this->createMock(BonusCalculationPolicyFactoryInterface::class);
        $this->dateTimeProvider = $this->createMock(DateTimeProviderInterface::class);

        $this->reportUseCase = new GenerateSalaryReportUseCase(
            $this->departmentRepository,
            $this->employeeRepository,
            $departmentBonusRuleRepository,
            $this->remunerationReportRepository,
            $this->bonusCalculationPolicyFactory,
            $this->dateTimeProvider
        );
    }

    public function testDepartmentNotFound(): void
    {
        $this->employeeRepository->save(new Employee(3, 'Jan', 'Krawczyk', 11, 100, new DateTime('2010-10-10')));

        $this->dateTimeProvider
            ->expects($this->exactly(2))
            ->method('getCurrentDate')
            ->willReturn(new DateTime('2024-04-10'));

        $result = $this->reportUseCase->execute(new GenerateSalaryReportCommand());

        self::assertEquals(GenerateSalaryReportResult::departmentNotFound(), $result);
    }

    public function testBonusRuleNotFound(): void
    {
        $this->employeeRepository->save(new Employee(3, 'Jan', 'Krawczyk', 11, 100, new DateTime('2010-10-10')));
        $this->departmentRepository->save(new Department(11, 'Awesome department'));

        $this->dateTimeProvider
            ->expects($this->exactly(2))
            ->method('getCurrentDate')
            ->willReturn(new DateTime('2024-04-10'));

        $result = $this->reportUseCase->execute(new GenerateSalaryReportCommand());

        self::assertEquals(GenerateSalaryReportResult::departmentBonusRuleNotFound(), $result);
    }

    public function testWhenBonusCalculatorCanNotBeCreated(): void
    {
        $this->dateTimeProvider
            ->expects($this->never())
            ->method('getCurrentDate');

        $this->bonusCalculationPolicyFactory
            ->expects($this->once())
            ->method('create')
            ->willThrowException(new BonusCalculationPolicyCanNotBeCreatedException());

        $result = $this->reportUseCase->execute(new GenerateSalaryReportCommand());

        self::assertEquals(GenerateSalaryReportResult::bonusCalculatorCanNotBeCreated(), $result);
    }

    public function testSuccess(): void
    {
        $this->dateTimeProvider
            ->expects($this->exactly(2))
            ->method('getCurrentDate')
            ->willReturn(new DateTime('2024-04-10'));

        $policy1 = $this->createMock(BonusCalculationPolicy::class);
        $policy1
            ->expects($this->once())
            ->method('calculateBonus')
            ->with(1650, 3)
            ->willReturn(150.5);

        $policy2 = $this->createMock(BonusCalculationPolicy::class);
        $policy2
            ->expects($this->once())
            ->method('calculateBonus')
            ->with(2250, 6)
            ->willReturn(100.0);

        $this->bonusCalculationPolicyFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->willReturnOnConsecutiveCalls(
                $policy1,
                $policy2
            );

        $result = $this->reportUseCase->execute(new GenerateSalaryReportCommand());

        self::assertEquals(GenerateSalaryReportResult::success(), $result);
        self::assertEquals(
            [
                new RemunerationReport(1, 'Jan', 'Kowalski', 'HR', 1650.0, 150.5, 'percentage', 1800.5),
                new RemunerationReport(2, 'John', 'Testowy', 'IT', 2250.0, 100.0, 'fixed', 2350),
            ],
            $this->remunerationReportRepository->findAll()
        );
    }


}
