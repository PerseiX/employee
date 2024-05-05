<?php

declare(strict_types=1);

namespace App\Payment\UI\Command;

use App\Payment\Application\Query\RemunerationReports\Filter;
use App\Payment\Application\Query\RemunerationReports\Order;
use App\Payment\Application\Query\RemunerationReports\RemunerationReportQueryInterface;
use App\Payment\Application\UseCase\RemunerationReport\Delete\DeleteAllReportsUseCasePort;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportCommand as GenerateSalaryReportUseCaseCommand;
use App\Payment\Application\UseCase\RemunerationReport\Generate\GenerateSalaryReportUseCasePort;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:generate-remuneration-report')]
class GenerateSalaryReportCommand extends Command
{
    public function __construct(
        private readonly GenerateSalaryReportUseCasePort $reportUseCase,
        private readonly RemunerationReportQueryInterface $remunerationReportQuery,
        private readonly DeleteAllReportsUseCasePort $deleteAllReportsUseCase
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->deleteAllReportsUseCase->execute();
        $output->writeln('<info>All reports have been deleted</info>');

        $reportStatus = $this->reportUseCase->execute(new GenerateSalaryReportUseCaseCommand());

        if (!$reportStatus->isSuccess()) {
            $output->writeln('<error>Internal error and the report can not be generated.</error>');

            return self::FAILURE;
        }

        $report = $this->remunerationReportQuery->execute(
            $this->getFilter($input, $output),
            $this->getOrder($input, $output)
        );

        $table = new Table($output);
        $table
            ->setHeaders(
                ['ID', 'Name', 'Surname', 'Department', 'Remuneration', 'Bonus', 'Bonus Type', 'Final Salary']
            )
            ->setRows($report);
        $table->render();

        return Command::SUCCESS;
    }


    private function getFilter(InputInterface $input, OutputInterface $output): Filter
    {
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion(
            'Do you want to filter?',
            ['yes', 'no'],
            'no'
        );
        $question->setErrorMessage('Invalid %s decision.');

        $wantToFilter = $helper->ask($input, $output, $question);

        if ('no' === $wantToFilter) {
            return new Filter();
        }

        $question = new ChoiceQuestion(
            'By what field do you want to filter??',
            ['name', 'surname', 'department'],
            'name'
        );
        $question->setErrorMessage('Invalid %s filter.');
        $fieldToFilter = $helper->ask($input, $output, $question);

        $question = new Question('Please enter the value: ');

        $value = $helper->ask($input, $output, $question);

        return match ($fieldToFilter) {
            'name' => new Filter(name: $value),
            'surname' => new Filter(surname: $value),
            'department' => new Filter(department: $value),
        };
    }

    private function getOrder(InputInterface $input, OutputInterface $output): Order
    {
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion(
            'Do you want to sort?',
            ['yes', 'no'],
            'no'
        );
        $question->setErrorMessage('Invalid %s decision.');

        $wantToSort = $helper->ask($input, $output, $question);

        if ('no' === $wantToSort) {
            return new Order();
        }

        $question = new ChoiceQuestion(
            'By what column do you wan to sort? ',
            ['name', 'surname', 'department', 'remuneration', 'bonus', 'bonusType', 'finalSalary'],
            0
        );

        $question->setErrorMessage('Invalid %s field to sort.');

        $field = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
            'What the sorting direction do you want?  ',
            ['asc', 'desc'],
            0
        );
        $question->setErrorMessage('Invalid %s sorting direction.');

        $direction = $helper->ask($input, $output, $question);

        return new Order($direction, $field);
    }
}
