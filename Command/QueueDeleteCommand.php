<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Queue\Factory\QueueFactoryInterface;
use Resque\Component\Queue\Registry\QueueRegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Queue delete.
 */
class QueueDeleteCommand extends Command
{
    const CONFIRM_MSG = 'The "%s" queue will be purged. Do you want to continue?';

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
            ->setName('resque:queue:delete')
            ->setDescription('Clears and deregisters the given queue queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $queue = $this->queueFactory->createQueue($input->getArgument('queue'));

        if ($input->isInteractive()) {
            if (!$output->confirm(sprintf(static::CONFIRM_MSG, $queue->getName()), false)) {
                return;
            }
        }

        $count = $this->queueRegistry->deregister($queue);

        $output->success('Deleted queue "' . $queue->getName() . '"');

        if ($count) {
            $output->note('Removed ' . $count . ' jobs.');
        }
    }
}
