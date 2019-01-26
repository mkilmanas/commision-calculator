<?php

namespace App\Command;

use App\CommissionCalculator;
use App\Model\Currency;
use App\Model\Transaction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalculateCommissionsCommand extends Command
{
    /**
     * @var CommissionCalculator
     */
    private $calculator;

    public function __construct(CommissionCalculator $calculator)
    {
        parent::__construct("commissions:calculate");

        $this->setDescription("Calculates Commission amounts for transactions in the input file");
        $this->addArgument('file', InputArgument::REQUIRED, "CSV data file listing the transactions");
        $this->calculator = $calculator;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        if (empty($file)) {
            throw new InvalidArgumentException("File parameter cannot be empty");
        }
        if (!is_file($file)) {
            throw new RuntimeException("File '{$file}' was not found");
        }
        if (!$f = fopen($file, 'r')) {
            throw new RuntimeException("Cannot open file '{$file}' for reading");
        }

        $io = new SymfonyStyle($input, $output);

        while (($row = fgetcsv($f)) !== false) {
            if (empty($row)) {
                continue;
            }

            try {
                $transaction = new Transaction(
                    new \DateTimeImmutable($row[0]),
                    (int)$row[1],
                    $row[2],
                    $row[3],
                    floatval($row[4]),
                    call_user_func([Currency::class, $row[5]])
                );
                $this->calculator->calculateFee($transaction);

                $decimals = $transaction->getCurrency()->getDecimalPlaces();

                $output->writeln(sprintf("%.{$decimals}f", $transaction->getFee()));
            } catch (\Throwable $e) {
                $io->error("Could not calculate commissions due to error: {$e->getMessage()}");
                return 1;
            }
        }

        $io->success("Done calculating commissions");
    }
}
