<?php
/**
 * OrderSubscriber.php
 *
 * @since 29/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProcessorInterface
     */
    private $trackingProcessor;

    /**
     * OrderSubscriber constructor.
     *
     * @param ProcessorInterface $trackingProcessor
     */
    public function __construct(ProcessorInterface $trackingProcessor)
    {
        $this->trackingProcessor = $trackingProcessor;
    }

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_UPDATE => 'update',
        ];
    }

    public function update(ResourceControllerEvent $event)
    {
        if($event->getSubject() instanceof OrderInterface) {
            $this->trackingProcessor->process($event->getSubject());
        }
    }
}