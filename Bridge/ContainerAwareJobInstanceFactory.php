<?php

namespace Resque\Bundle\ResqueBundle\Bridge;

use Resque\Component\Job\Factory\JobInstanceFactory;
use Resque\Component\Job\Model\JobInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Service aware job instance factory.
 *
 * This brings the ability to target application services as jobs, and not just a class.
 */
class ContainerAwareJobInstanceFactory extends JobInstanceFactory implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function createPerformantJob(JobInterface $job)
    {
        $target = $job->getJobClass();

        if ($this->container->has($target)) {
            return $this->container->get($target);
        }

        return parent::createPerformantJob($job);
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
