<?php

namespace Resque\Bundle\ResqueBundle\Tests\Command;

use Resque\Bundle\ResqueBundle\Command\JobEnqueueCommand;
use Resque\Bundle\ResqueBundle\Test\CommandTestCase;

class JobEnqueueCommandTest extends CommandTestCase
{
    public function testExecution()
    {
        $tester = $this->createQueueListTester();
        $exitCode = $tester->execute(array(), array());

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
    }

    private function createQueueListTester()
    {
        $command = new JobEnqueueCommand(
            $this->getMock('Resque\Component\Queue\Registry\QueueRegistryInterface'),
            $this->getMock('Resque\Component\Queue\Factory\QueueFactoryInterface')
        );

        return $this->createCommandTester('resque:enqueue', $command);
    }
}
