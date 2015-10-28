<?php

namespace Resque\Bundle\ResqueBundle\Test;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * DRY for command testing.
 */
abstract class CommandTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Create command tester.
     *
     * @param string $commandName The command name.
     * @param Command $command The command you're testing.
     * @return CommandTester
     */
    protected function createCommandTester($commandName, Command $command)
    {
        $application = new Application();

        $application->add($command);

        return new CommandTester($application->find($commandName));
    }
}
