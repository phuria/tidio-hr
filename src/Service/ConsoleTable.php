<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class ConsoleTable
{
    private array $headers = [];

    private array $rows = [];

    private bool $filter = false;

    private bool $sort = false;

    public function __construct(
        private QuestionHelper $questionHelper,
        private InputInterface $input,
        private OutputInterface $output,
    ) {
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function addRow(array $row): void
    {
        $this->rows[] = $row;
    }

    public function enableFilter(): void
    {
        $this->filter = true;
    }

    public function enableSort(): void
    {
        $this->sort = true;
    }

    public function render(): void
    {
        $table = new Table($this->output);
        $table->setHeaders($this->headers);

        $rows = $this->rows;

        if ($this->filter) {
            $rows = $this->filter($rows);
        }

        if ($this->sort) {
            $rows = $this->sort($rows);
        }

        $table->setRows($rows);
        $table->render();
    }

    private function sort(array $rows): array
    {
        $column = $this->selectColumn('Select column you want to sort: ');

        usort($rows, static function (array $a, array $b) use ($column): int {
           return $a[$column] <=> $b[$column];
        });

        return $rows;
    }

    private function filter(array $rows): array
    {
        $column = $this->selectColumn('Select column you want to filter: ');
        $name = $this->headers[$column];

        $value = $this->questionHelper->ask(
            $this->input,
            $this->output,
            new Question("{$name}: ")
        );

        return array_filter($rows, static function (array $row) use ($column, $value): bool {
            return str_contains($row[$column], $value);
        });
    }

    private function selectColumn(string $message): string
    {
        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            new ChoiceQuestion($message, $this->headers)
        );
    }
}
