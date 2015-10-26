<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JobEnqueueCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('resque:enqueue')
            ->setDescription('Enqueues a job into the resque system')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
