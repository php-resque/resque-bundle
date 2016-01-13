<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Core\Foreman;
use Resque\Component\Queue\Registry\QueueRegistryInterface;
use Resque\Component\Queue\WildcardQueue;
use Resque\Component\Worker\Factory\WorkerFactoryInterface;
use Resque\Component\Worker\Registry\WorkerRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WorkerStartCommand extends Command
{
    /**
     * @var Foreman
     */
    protected $foreman;

    /**
     * Constructor.
     *
     * @param Foreman $foreman
     */
    public function __construct(
        QueueRegistryInterface $queueRegistry,
        WorkerFactoryInterface $workerFactory,
        Foreman $foreman
    ) {
        $this->queueRegistry = $queueRegistry;
        $this->workerFactory = $workerFactory;
        $this->foreman = $foreman;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('resque:worker:start')
            ->setDescription('Starts a new worker process on this host')
            ->addArgument('queues', InputArgument::REQUIRED, 'The queue names (in order) the workers will retrieve jobs from')
            ->addOption('count', null, InputOption::VALUE_OPTIONAL, 'The number of workers to start', 1)
            ->addOption('skip-prune', null, InputOption::VALUE_OPTIONAL, 'If you want to skip the pruning dead workers', true)
            ->addOption('wait', null, InputOption::VALUE_OPTIONAL, 'If you want the console command to wait for the workers to exit', false)
        ;
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

        $workerCount = $input->getOption('count');
        $queues = $this->createQueues($input->getArgument('queues'));

        $output->write($queues);

        // This method of worker setup requires an array of queues
        if (!is_array($queues)) {
            throw new \Exception("Queues not initialized correctly");
        }
        $workers = array();
        for ($i = 0; $i < $workerCount; ++$i) {
            $worker = $this->workerFactory->createWorker();
            $workerProcess = $this->workerFactory->createWorkerProcess($worker);

            foreach ($queues as $queue) {
                $worker->addQueue($queue);
            }

            $workers[] = $workerProcess;
        }

        $this->foreman->pruneDeadWorkers();

        $this->foreman->work($workers, $input->getOption('wait'));

        echo sprintf(
            '%d workers attached to the %s queues successfully started.',
            count($workers),
            implode($queues, ',')
        );

//        echo sprintf(
//            'Workers (%s)',
//            implode(', ', $workers)
//        );
    }

    protected function createQueues($queuesArg)
    {
        $configQueues = explode(',', $queuesArg);

        $queues = array();
        if (in_array('*', $configQueues)) {
            // @todo service this.
            $wildcard = new WildcardQueue($this->queueRegistry);
            $queues[] = $wildcard;
        } else {
            foreach ($configQueues as $configQueue) {
                $queues[] = $this->queueRegistry->createQueue($configQueue);
            }
        }

        return $queues;
    }
}

