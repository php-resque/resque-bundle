<?php

namespace Resque\Bundle\ResqueBundle\Tests\Command;

use Resque\Bundle\ResqueBundle\Command\QueueDeleteCommand;
use Resque\Bundle\ResqueBundle\Test\CommandTestCase;

class QueueDeleteCommandTest extends CommandTestCase
{
    public function testExecution()
    {
        $tester = $this->createQueueDeleteTester();
        $exitCode = $tester->execute(array(), array());

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertContains('Name   Method   Scheme   Host   Path', $tester->getDisplay());
    }

    private function createQueueDeleteTester()
    {
        $command = new QueueDeleteCommand($this->getMock('Resque\Component\Queue\Registry\QueueRegistryInterface'));

        return $this->createCommandTester('resque:queue:list', $command);
    }
}
