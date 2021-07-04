<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\Department;
use App\Model\Money;
use App\Model\Percentage;
use App\Model\SalaryBonus;
use App\Model\SalaryBonusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class CreateDepartmentCommand extends Command
{
    protected static $defaultName = 'hr:create-department';

    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question('Department Name: ');
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Department Shortcode: ');
        $shortcode = $helper->ask($input, $output, $question);

        $department = new Department(
            $name,
            $shortcode,
            $this->getSalaryBonus($helper, $input, $output)
        );

        $this->em->persist($department);
        $this->em->flush();

        $output->writeln("<info>Department '{$department->getName()}' created.</info>");

        return self::SUCCESS;
    }

    private function getSalaryBonus(QuestionHelper $helper, InputInterface $input, OutputInterface $output): SalaryBonus
    {
        $question = new ChoiceQuestion('Salary Bonus Type: ', SalaryBonusType::getChoices());
        $bonusType = new SalaryBonusType($helper->ask($input, $output, $question));

        if ($bonusType->isEquals(SalaryBonusType::PERCENTAGE())) {
            $question = new Question('Salary Bonus Percentage (ex. 12): ');
            $question->setValidator(static function (string $answer): SalaryBonus {
                return SalaryBonus::percentage(new Percentage((int) $answer));
            });
        } else {
            $question = new Question('Salary Bonus (in USD per year, ex. 100.00): ');
            $question->setValidator(static function (string $answer): SalaryBonus {
                return SalaryBonus::fixed(new Money($answer));
            });
        }

        return $helper->ask($input, $output, $question);
    }
}
