<?php

namespace Resque\Bundle\ResqueBundle\Tests\Command;

use Resque\Bundle\ResqueBundle\Command\QueueListCommand;
use Resque\Bundle\ResqueBundle\Command\WorkerStartCommand;
use Resque\Bundle\ResqueBundle\Test\CommandTestCase;

class WorkerStartCommandTest extends CommandTestCase
{
    public function testExecution()
    {
        $tester = $this->createQueueListTester();
        $exitCode = $tester->execute(array('queues' => 'high,low'), array());

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
    }

    private function createQueueListTester()
    {
        $command = new WorkerStartCommand($this->getMock('Resque\Component\Worker\Registry\WorkerRegistryInterface'));

        return $this->createCommandTester('resque:worker:start', $command);
    }
}
