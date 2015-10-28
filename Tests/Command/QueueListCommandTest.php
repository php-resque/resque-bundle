<?php

namespace Resque\Bundle\ResqueBundle\Tests\Command;

use Resque\Bundle\ResqueBundle\Command\QueueListCommand;
use Resque\Bundle\ResqueBundle\Test\CommandTestCase;

class QueueListCommandTest extends CommandTestCase
{
    public function testExecution()
    {
        $tester = $this->createQueueListTester();
        $exitCode = $tester->execute(array(), array());

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertContains('Name   Method   Scheme   Host   Path', $tester->getDisplay());
    }

    private function createQueueListTester()
    {
        $command = new QueueListCommand($this->getMock('Resque\Component\Queue\Registry\QueueRegistryInterface'));

        return $this->createCommandTester('resque:queue:list', $command);
    }
}
