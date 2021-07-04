<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Department;
use App\Model\Employee;
use App\Model\Money;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class CreateEmployeeCommand extends Command
{
    protected static $defaultName = 'hr:create-employee';

    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $departmentChoices = $this->getDepartmentChoices();

        if (0 === count($departmentChoices)) {
            $output->writeln('No one department available.');
            return self::FAILURE;
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion('Choose new employee department: ', $departmentChoices);
        $question->setValidator(function (string $shortCode) {
            return $this->em->getRepository(Department::class)->findOneBy([
                'shortCode' => $shortCode,
            ]);
        });
        $department = $helper->ask($input, $output, $question);

        $question = new Question('Employee Firstname: ');
        $question->setTrimmable(true);
        $firstname = $helper->ask($input, $output, $question);

        $question = new Question('Employee Lastname: ');
        $question->setTrimmable(true);
        $lastname = $helper->ask($input, $output, $question);

        $question = new Question('Employee Base Salary in USD (ex. 1000.00): ');
        $question->setValidator(static function (string $answer) {
            return new Money($answer);
        });
        $baseSalary = $helper->ask($input, $output, $question);

        $question = new Question('Date Of Employment YYYY-MM (ex. 2021-01-01): ', (new DateTime())->format('Y-m-d'));
        $question->setValidator(static function (string $answer) {
           return new DateTimeImmutable($answer);
        });
        $employedAt = $helper->ask($input, $output, $question);

        $employee = new Employee($firstname, $lastname, $department, $baseSalary, $employedAt);

        $this->em->persist($employee);
        $this->em->flush();

        $output->writeln("<info>Employee '{$employee->toString()}' created.</info>");

        return self::SUCCESS;
    }

    private function getDepartmentChoices(): array
    {
        $departments = $this->em->getRepository(Department::class)->findAll();
        $choices = [];

        /** @var Department $department */
        foreach ($departments as $department) {
            $choices[$department->getShortCode()] = $department->getName();
        }

        return $choices;
    }
}
