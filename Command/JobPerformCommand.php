<?php

namespace Resque\Bundle\ResqueBundle\Command;

use Resque\Component\Job\Model\Job;
use Resque\Component\Worker\JobPerformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class JobPerformCommand extends Command
{
    /**
     * @var JobPerformer
     */
    protected $jobPerformer;

    /**
     * Constructor.
     *
     * @param JobPerformer $jobPerformer
     */
    public function __construct(JobPerformer $jobPerformer)
    {
        $this->jobPerformer = $jobPerformer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('resque:perform')
            ->setDescription('Performs the given job')
            ->addArgument('target', InputArgument::REQUIRED, 'The job name or target name')
            ->addArgument('arguments', InputArgument::OPTIONAL, 'The arguments for the target job')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);

        $job = new Job($input->getArgument('target'));  // @todo remove new Job. @todo arguments

        if ($input->isInteractive()) {
            if (!$output->confirm(sprintf('you sure?', $job->getJobClass()), false)) {
                return;
            }
        }

        if (true === ($result = $this->jobPerformer->perform($job))) {
            $output->success('Job "' . $job->getJobClass() . '" with arguments (?) successfully performed');
        } else {
            throw $result;
        }
    }
}
