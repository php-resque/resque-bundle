<?php

namespace Resque\Bundle\ResqueBundle\ArrayAdapter;

use SplQueue;
use Resque\Component\Job\Model\Job;
use Resque\Component\Job\Model\JobInterface;
use Resque\Component\Queue\Model\OriginQueueAwareInterface;
use Resque\Component\Queue\Model\QueueInterface;
use Resque\Component\Queue\Storage\QueueStorageInterface;

class ArrayQueueStorage implements
    QueueStorageInterface
{
    /**
     * @var SplQueue
     */
    protected $storage;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->storage = new SplQueue();
    }

    /**
     * {@inheritDoc}
     */
    public function enqueue(QueueInterface $queue, JobInterface $job)
    {
        $this->storage->enqueue($job);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function dequeue(QueueInterface $queue)
    {
        if (!$this->storage->count()) {
            return null;
        }

        $job = $this->storage->dequeue();

        if (!$job) {
            return null;
        }

        if ($job instanceof OriginQueueAwareInterface) {
            $job->setOriginQueue($queue);
        }

        return $job;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(QueueInterface $queue, $filter = array())
    {
        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function count(QueueInterface $queue)
    {
        return $this->storage->count();
    }
}
