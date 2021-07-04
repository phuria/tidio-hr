<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Employee;
use App\Model\Month;
use App\Service\BonusCalculator\CalculateException;
use App\Service\ConsoleTable;
use App\Service\PayoutFactory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowPayoutsCommand extends Command
{
    protected static $defaultName = 'hr:show-payouts';

    public function __construct(
        private EntityManagerInterface $em,
        private PayoutFactory $payoutFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'month',
            'm',
            InputOption::VALUE_REQUIRED,
            'Payout month [eq. 2021-01]',
            (new DateTime())->format('Y-m')
        );

        $this->addOption('filter', 'f', InputOption::VALUE_NONE, 'Filter results');
        $this->addOption('sort', 's', InputOption::VALUE_NONE, 'Sort results');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $payoutMonth = Month::fromString($input->getOption('month'));
        $employees = $this->em->getRepository(Employee::class)->findAll();

        $table = new ConsoleTable($this->getHelper('question'), $input, $output);
        $table->setHeaders([
            'fn' => 'Firstname',
            'ln' => 'Lastname',
            'd' => 'Department',
            'bs' => 'Base Salary',
            'sb' => 'Salary Bonus',
            'bt' => 'Bonus Type',
            'fs' => 'Final Salary',
        ]);

        if ($input->getOption('filter')) {
            $table->enableFilter();
        }

        if ($input->getOption('sort')) {
            $table->enableSort();
        }

        /** @var Employee $employee */
        foreach ($employees as $employee) {
            try {
                $payout = $this->payoutFactory->create($payoutMonth, $employee);
            } catch (CalculateException) {
                $payout = null;
            }

            $table->addRow([
                'fn' => $employee->getFirstname(),
                'ln' => $employee->getLastname(),
                'd' => $employee->getDepartmentShortCode(),
                'bs' => $employee->getBaseSalary()->toString(),
                'sb' => $payout ? $payout->getSalaryBonus()->toString() : 'n/d',
                'bt' => $employee->getSalaryBonusType()->toString(),
                'fs' => $payout ? $payout->getFinalSalary()->toString() : 'n/d',
            ]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
