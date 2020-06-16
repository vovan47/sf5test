<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends Command
{
    const TYPE_PRODUCT = 'product';
    const TYPE_CATEGORY = 'category';

    protected function configure()
    {
        $this
            ->setName('app:import-data')
            ->setDescription('Imports data from JSON file')
            ->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Entity type: product or category'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');

        if (!in_array($type, [self::TYPE_PRODUCT, self::TYPE_CATEGORY])) {
            throw new \InvalidArgumentException('Wrong type option specified');
        }

        $output->writeln('Starting import');
        $output->writeln('Finished import');
        return 0;
    }
}