<?php
/**
 * EventDispatcher.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher as SyliusEventDispatcher;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

class EventDispatcher extends SyliusEventDispatcher
{
    /**
     * @var SymfonyEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param SymfonyEventDispatcherInterface $eventDispatcher
     */
    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchPreEvent($eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource)
    {
        parent::dispatchPreEvent($eventName, $requestConfiguration, $resource);
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%', $eventName), new ResourceControllerEvent($resource));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchPostEvent($eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource)
    {
        parent::dispatchPostEvent($eventName, $requestConfiguration, $resource);
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%', $eventName), new ResourceControllerEvent($resource));
    }
}