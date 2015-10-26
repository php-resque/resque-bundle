<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Worker\Registry\WorkerRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WorkerListCommand extends Command
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
            ->setName('resque:worker:list')
            ->setDescription('Lists all known workers and the number of jobs they have');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $workers = $this->workerRegistry->all();

        $tableInput = array();

        foreach ($workers as $worker) {
            $tableInput[] = array($worker->getId(), $worker->getHostname(), $worker->getProcess()->getPid(), $worker->getCurrentJob());
        }

        $output->table(array('Id', 'Hostname', 'Pid', 'Current job'), $tableInput);
    }
}
