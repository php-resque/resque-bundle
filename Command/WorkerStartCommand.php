<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Worker\Registry\WorkerRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WorkerStartCommand extends Command
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
            ->setName('resque:worker:start')
            ->setDescription('Starts a new worker process on this host')
            ->addArgument('queues', InputArgument::REQUIRED, 'The queue names (in order) the workers will retrieve jobs from')
            ->addArgument('count', InputArgument::OPTIONAL, 'The number of workers to start', 1);
//
//            'app_include' => getenv('APP_INCLUDE'),
//            'queue_blocking' => (bool)getenv('BLOCKING'),
//            'queue_interval' => false === getenv('INTERVAL') ? 5 : getenv('INTERVAL'),
//            'redis_prefix' => false === getenv('PREFIX') ? 'resque' : getenv('PREFIX'),
//            'redis_dsn' => getenv('REDIS_BACKEND'),
//            'logging' => (bool)getenv('LOGGING'),
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $queues = $input->getArgument('queues');

        $output->write($queues);
    }
}
