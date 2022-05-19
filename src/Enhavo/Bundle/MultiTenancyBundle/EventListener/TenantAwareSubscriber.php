<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.07.18
 * Time: 17:46
 */

namespace Enhavo\Bundle\MultiTenancyBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TenantAwareSubscriber implements EventSubscriberInterface
{
    /** @var TenantManager */
    private $manager;

    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => ['preCreate', 10]    // Needs to run before RoutingBundle AutoGenerators
        );
    }

    public function preCreate(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof TenantAwareInterface) {
            $resource->setTenant($this->manager->getTenant()->getKey());
        }
    }
}
