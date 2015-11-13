<?php

namespace Resque\Bundle\ResqueBundle\ArrayAdapter;

use Resque\Component\Queue\Model\QueueInterface;
use Resque\Component\Queue\Registry\QueueRegistryAdapterInterface;

class ArrayQueueRegistryAdapter implements
    QueueRegistryAdapterInterface
{
    /**
     * @var QueueInterface[]
     */
    protected $queues = array();

    /**
     * {@inheritDoc}
     */
    public function save(QueueInterface $queue)
    {
        $this->queues[$queue->getName()] = $queue;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function has(QueueInterface $queue)
    {
        return array_key_exists($queue->getName(), $this->queues);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(QueueInterface $queue)
    {
        if (!isset($this->queues[$queue->getName()])) {
            return 0;
        }

        $queue = $this->queues[$queue->getName()];

        $jobCount = $queue->count();

        unset($this->queues[$queue->getName()]);

        return $jobCount;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return array_keys($this->queues);
    }
}
