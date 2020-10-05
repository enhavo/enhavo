<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.07.18
 * Time: 17:46
 */

namespace Enhavo\Bundle\MultiTenancyBundle\EventListener;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantAwareInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

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
            'enhavo_app.pre_create' => 'preCreate'
        );
    }

    public function preCreate(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof TenantAwareInterface) {
            $resource->setTenant($this->manager->getTenant()->getKey());
        }
    }
}
