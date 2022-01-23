<?php

namespace Ravenfire\Magpie\Application\SqlScripts;

use Illuminate\Database\Capsule\Manager as DB;
use Ravenfire\Magpie\Application\AbstractMagpieCommand;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SqlJoinScript extends AbstractMagpieCommand
{
    protected static $defaultName = 'sql:join';
    protected static $defaultDescription = "Sql query counting the number of every group in a column";

    protected function configure(): void
    {
        $this->setHelp("Sql query counting the number of every group in a column");
        $this->addArgument('tableOne', InputArgument::REQUIRED, "First table to use");
        $this->addArgument('tableTwo', InputArgument::REQUIRED, "Second table to use");
        $this->addArgument('tableOneJoinColumn', InputArgument::REQUIRED, "Table one column to join");
        $this->addArgument('tableTwoJoinColumn', InputArgument::REQUIRED, "Table two column to join");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContext()->getLogger()->pushHandler(new ConsoleHandler($output));

        $tableOne = $input->getArgument('tableOne');
        $tableTwo = $input->getArgument('tableTwo');
        $tableOneJoinColumn = $input->getArgument('tableOneJoinColumn');
        $tableTwoJoinColumn = $input->getArgument('tableTwoJoinColumn');

        $results = $this->index($tableOne, $tableTwo, $tableOneJoinColumn, $tableTwoJoinColumn);

        foreach ($results[0] as $result => $data) {
            $dbColumns[] = $result;
        }

        $rows = [];
        foreach ($results as $result) {
            $row = [];
            foreach ($dbColumns as $dbColumn) {
                if (strlen($result->$dbColumn) > 12) {
                    $result->$dbColumn = substr($result->$dbColumn, 0, 12);
                }
                $row[] = $result->$dbColumn;
            }
            $rows[] = $row;
        }

        $table_helper = new Table($output);
        $table_helper->setRows($rows);
        $table_helper->setHeaders($dbColumns);
        $table_helper->render();

        $this->getContext()->getLogger()->info("Done");

        return COMMAND::SUCCESS;
    }

    public function index($tableOne, $tableTwo, $tableOneJoinColumn, $tableTwoJoinColumn)
    {
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM {$tableOne} ";
        $sql .= "JOIN {$tableTwo} ON {$tableOneJoinColumn} = {$tableTwoJoinColumn} ";
        $sql .= "LIMIT 10";

        return DB::select($sql);
    }
}