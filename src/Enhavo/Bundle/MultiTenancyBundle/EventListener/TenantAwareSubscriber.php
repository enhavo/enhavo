<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\EventListener;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
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
        return [
            ResourceEvents::PRE_CREATE => ['preCreate', 10],    // Needs to run before RoutingBundle AutoGenerators
        ];
    }

    public function preCreate(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if ($resource instanceof TenantAwareInterface) {
            $resource->setTenant($this->manager->getTenant()->getKey());
        }
    }
}
