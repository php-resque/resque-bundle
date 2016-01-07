<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Worker\Registry\WorkerRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WorkerStopAllCommand extends Command
{
    /**
     * @var WorkerRegistryInterface
     */
    protected $workerRegistry;

    /**
     * Constructor.
     *
     * @param WorkerRegistryInterface $workerRegistry
     */
    public function __construct(WorkerRegistryInterface $workerRegistry)
    {
        $this->workerRegistry = $workerRegistry;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('resque:worker:stop-all')
            ->setDescription('Stops all known local workers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
    }
}
