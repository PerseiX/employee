<?php

namespace App\Tests\functional\Payment\UI\Command;

use App\Payment\Application\Query\RemunerationReports\Filter;
use App\Payment\Application\Query\RemunerationReports\Order;
use App\Payment\Application\Query\RemunerationReports\RemunerationReportQueryInterface;
use App\Payment\Application\UseCase\RemunerationReport\Delete\DeleteAllReportsResult;
use App\Payment\Application\UseCase\RemunerationReport\Delete\DeleteAllReportsUseCasePort;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportCommand as GenerateSalaryReportCommandUseCase;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportResult;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportUseCase;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportUseCasePort;
use App\Payment\Ui\Command\GenerateSalaryReportCommand;
use Doctrine\ORM\Query\AST\DeleteClause;
use Emag\SdfBundle\Application\Tradebyte\Create\UseCase;
use Emag\SdfBundle\Application\Tradebyte\Create\UseCasePort;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateSalaryReportCommandTest extends KernelTestCase
{
    private GenerateSalaryReportUseCasePort|MockObject $reportUseCase;
    private RemunerationReportQueryInterface|MockObject $remunerationReportQuery;
    private DeleteAllReportsUseCasePort|MockObject $deleteAllReportsUseCase;
    private GenerateSalaryReportCommand $command;

    public function setUp(): void
    {
        self::bootKernel();

        $this->reportUseCase = $this->createMock(GenerateSalaryReportUseCasePort::class);
        $this->remunerationReportQuery = $this->createMock(RemunerationReportQueryInterface::class);
        $this->deleteAllReportsUseCase = $this->createMock(DeleteAllReportsUseCasePort::class);

        self::$kernel->getContainer()->set(GenerateSalaryReportUseCasePort::class, $this->reportUseCase);
        self::$kernel->getContainer()->set(RemunerationReportQueryInterface::class, $this->remunerationReportQuery);
        self::$kernel->getContainer()->set(DeleteAllReportsUseCasePort::class, $this->deleteAllReportsUseCase);

        $application = new Application(self::$kernel);

        $this->command = $application->find('app:generate-remuneration-report');
    }

    public function testReportNotGenerated(): void
    {
        $this->deleteAllReportsUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(DeleteAllReportsResult::success());

        $this->reportUseCase
            ->expects($this->once())
            ->method('execute')
            ->with(new GenerateSalaryReportCommandUseCase())
            ->willReturn(GenerateSalaryReportResult::departmentNotFound());

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['no']);

        self::assertStringContainsString('Internal error and the report can not be generated.', $commandTester->getDisplay());

    }

    public function testSuccessEmptyResult(): void
    {
        $this->deleteAllReportsUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(DeleteAllReportsResult::success());

        $this->reportUseCase
            ->expects($this->once())
            ->method('execute')
            ->with(new GenerateSalaryReportCommandUseCase())
            ->willReturn(GenerateSalaryReportResult::success());

        $this->remunerationReportQuery
            ->expects($this->once())
            ->method('execute')
            ->with(new Filter(), new Order())
            ->willReturn([]);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['no']);
    }

    public function testSuccessOneResult(): void
    {
        $this->deleteAllReportsUseCase
            ->expects($this->once())
            ->method('execute')
            ->willReturn(DeleteAllReportsResult::success());

        $this->reportUseCase
            ->expects($this->once())
            ->method('execute')
            ->with(new GenerateSalaryReportCommandUseCase())
            ->willReturn(GenerateSalaryReportResult::success());

        $this->remunerationReportQuery
            ->expects($this->once())
            ->method('execute')
            ->with(new Filter(), new Order())
            ->willReturn([[1, 'Test', 'Surname test', 'IT', 1300, 150, 'fixed', 1450]]);

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['no']);

        self::assertStringContainsString(
            <<<TABLE
            | ID | Name | Surname      | Department | Remuneration | Bonus | Bonus Type | Final Salary |
            +----+------+--------------+------------+--------------+-------+------------+--------------+
            | 1  | Test | Surname test | IT         | 1300         | 150   | fixed      | 1450         |
            +----+------+--------------+------------+--------------+-------+------------+--------------+
            TABLE,
            $commandTester->getDisplay()
        );
    }
}
