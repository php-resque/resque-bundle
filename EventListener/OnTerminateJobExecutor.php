<?php

namespace Resque\Bundle\ResqueBundle\EventListener;

use Resque\Component\Job\Model\JobInterface;
use Resque\Component\Queue\Model\QueueInterface;
use Resque\Component\Queue\Registry\QueueRegistryInterface;
use Resque\Component\Worker\TaskPerformer;

class OnTerminateJobExecutor
{
    /**
     * @var QueueRegistryInterface
     */
    protected $queueRegistry;

    /**
     * Constructor.
     *
     * @param TaskPerformer $taskPerformer
     * @param QueueRegistryInterface $queueRegistry
     */
    public function __construct(
        TaskPerformer $taskPerformer,
        QueueRegistryInterface $queueRegistry
    ) {
        $this->taskPerformer = $taskPerformer;
        $this->queueRegistry = $queueRegistry;
    }

    public function executeOutstandingJobs()
    {
        $queues = $this->queueRegistry->all();

        foreach ($queues as $queue) {
            $this->processQueueJobs($queue);
        }
    }

    protected function processQueueJobs(QueueInterface $queue)
    {
        while ($job = $queue->dequeue()) {
            $this->executeJob($job);
        }
    }

    protected function executeJob(JobInterface $job)
    {
        $this->taskPerformer->perform($job);
    }
}
