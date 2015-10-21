<?php

namespace Resque\Bundle\ResqueBundle\Bridge;

use Resque\Component\Core\Event\EventDispatcherInterface as ResqueEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

/**
 * Event dispatcher bridge.
 *
 * This is an adapter to the ResqueEventDispatcherInterface so that we can expose Resque runtime events to
 * the rest of the Symfony application this bundle is in.
 */
class EventDispatcher implements ResqueEventDispatcherInterface
{
    /**
     * @var SymfonyEventDispatcherInterface
     */
    protected $symfonyDispatcher;

    /**
     * Constructor.
     *
     * @param SymfonyEventDispatcherInterface $symfonyDispatcher A Symfony event dispatcher.
     */
    public function __construct(SymfonyEventDispatcherInterface $symfonyDispatcher)
    {
        $this->symfonyDispatcher = $symfonyDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, $eventContext = null)
    {
        $this->symfonyDispatcher->dispatch($eventName);
    }
}
