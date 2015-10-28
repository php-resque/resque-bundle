<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Job\Model\Job;
use Symfony\Component\Console\Command\Command;
use Resque\Component\Queue\Factory\QueueFactoryInterface;
use Resque\Component\Queue\Registry\QueueRegistryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class JobEnqueueCommand extends Command
{
    /**
     * @var QueueRegistryInterface
     */
    protected $queueRegistry;

    /**
     * @var QueueFactoryInterface
     */
    protected $queueFactory;

    /**
     * Constructor.
     *
     * @param QueueRegistryInterface $queueRegistry
     * @param QueueFactoryInterface $queueFactory
     */
    public function __construct(QueueRegistryInterface $queueRegistry, QueueFactoryInterface $queueFactory)
    {
        $this->queueRegistry = $queueRegistry;
        $this->queueFactory = $queueFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('resque:enqueue')
            ->setDescription('Enqueues a job into the resque system')
            ->addArgument('queue', InputArgument::REQUIRED, 'The queue to enqueue the job on')
            ->addArgument('target', InputArgument::REQUIRED, 'The job name or target name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $queue = $this->queueFactory->createQueue($input->getArgument('queue'));

//        if ($input->isInteractive()) {
//            if (!$output->confirm(sprintf('you sure?', $queue->getName()), false)) {
//                return;
//            }
//        }

        $this->queueRegistry->register($queue); // @todo should this be here?

        $job = new Job($input->getArgument('target'));  // @todo remove new Job.

        if ($queue->enqueue($job)) {
            $output->success('Enqueued job "' . $job->getJobClass() . '" with arguments, to queue "' . $queue->getName() . '"');
        }
    }
}
