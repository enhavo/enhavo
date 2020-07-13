<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.07.18
 * Time: 17:46
 */

namespace Bundle\MultiTenancyBundle\EventListener;


use Bundle\MultiTenancyBundle\Model\MultiTenancyAwareInterface;
use Bundle\MultiTenancyBundle\Resolver\MultiTenancyResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

class MultiTenancyAwareSubscriber implements EventSubscriberInterface
{
    /**
     * @var MultiTenancyResolver
     */
    private $multiTenancyResolver;

    public function __construct(MultiTenancyResolver $multiTenancyResolver)
    {
        $this->multiTenancyResolver = $multiTenancyResolver;
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
        if($resource instanceof MultiTenancyAwareInterface) {
            $resource->setMultiTenancy($this->multiTenancyResolver->getKey());
        }
    }
}
