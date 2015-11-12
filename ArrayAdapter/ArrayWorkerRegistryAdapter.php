<?php

namespace Resque\Bundle\ResqueBundle\ArrayAdapter;

use Resque\Component\Worker\Model\WorkerInterface;
use Resque\Component\Worker\Registry\WorkerRegistryAdapterInterface;

class ArrayWorkerRegistryAdapter implements
    WorkerRegistryAdapterInterface
{
    protected $workers = array();

    /**
     * {@inheritDoc}
     */
    public function save(WorkerInterface $worker)
    {
        $this->workers[$worker->getId()] = $worker;
    }

    /**
     * {@inheritDoc}
     */
    public function has(WorkerInterface $worker)
    {
        return isset($this->workers[$worker->getId()]);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(WorkerInterface $worker)
    {
        if ($this->has($worker)) {
            unset($this->workers[$worker->getId()]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return array_keys($this->workers);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->workers);
    }
}
