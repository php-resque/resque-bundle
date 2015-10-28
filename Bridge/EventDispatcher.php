<?php

namespace Resque\Bundle\ResqueBundle\Bridge;

use Resque\Component\Core\Event\EventDispatcherInterface as ResqueEventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
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
        $symfonyEvent = $this->convertResqueEvent($eventContext);

        $this->symfonyDispatcher->dispatch($eventName, $symfonyEvent);

        // @todo if Resque events ever have mutable context, you would translate the Symfony event changes back
        //       in to the Resque event here.
    }

    /**
     * Convert Resque event to a Symfony compatible event.
     *
     * @param mixed|null $event A Resque event.
     *
     * @return null|Event A new Symfony event.
     */
    protected function convertResqueEvent($event)
    {
        if (null === $event) {
            return null;
        }

        return new Event();
    }
}
