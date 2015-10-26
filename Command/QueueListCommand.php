<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Queue\Factory\QueueFactoryInterface;
use Resque\Component\Queue\Registry\QueueRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class QueueListCommand extends Command
{
    /**
     * @var QueueRegistryInterface
     */
    protected $queueRegistry;

    /**
     * Constructor.
     *
     * @param QueueRegistryInterface $queueRegistry
     */
    public function __construct(QueueRegistryInterface $queueRegistry)
    {
        $this->queueRegistry = $queueRegistry;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('resque:queue:list')
            ->setDescription('Lists all known queues and the number of jobs they have');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $queues = $this->queueRegistry->all();

        $tableInput = array();

        foreach ($queues as $queue) {
            $tableInput[] = array($queue->getName(), $queue->count());
        }

        $output->table(array('Name', 'Outstanding jobs'), $tableInput);
    }
}
