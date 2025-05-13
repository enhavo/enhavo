<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\EventListener;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SoftDeleteListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'enhavo_resource.pre_soft_delete' => 'preSoftDelete',
            'enhavo_resource.pre_undelete' => 'preUndelete',
        ];
    }

    public function preSoftDelete(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if ($resource instanceof Routeable && $resource->getRoute() && is_subclass_of($resource, 'Enhavo\Bundle\RevisionBundle\Model\RevisionInterface')) {
            /** @var $resource \Enhavo\Bundle\RevisionBundle\Model\RevisionInterface&Routeable */
            $condition = $resource->getRoute()->getCondition();
            $staticPrefix = $resource->getRoute()->getStaticPrefix();
            $parameters = $resource->getRevisionParameters();
            $parameters['route_condition'] = $condition;
            $parameters['route_static_prefix'] = $staticPrefix;
            $resource->setRevisionParameters($parameters);

            $resource->getRoute()->setCondition('false');
            $resource->getRoute()->setStaticPrefix(sprintf('/soft_deleted_%s', $this->tokenGenerator->generateToken()));
        }
    }

    public function preUndelete(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if ($resource instanceof Routeable && $resource->getRoute() && is_subclass_of($resource, 'Enhavo\Bundle\RevisionBundle\Model\RevisionInterface')) {
            /** @var $resource \Enhavo\Bundle\RevisionBundle\Model\RevisionInterface&Routeable */
            $parameters = $resource->getRevisionParameters();
            $resource->getRoute()->setCondition($parameters['route_condition'] ?? '');
            if (isset($parameters['route_static_prefix'])) {
                $resource->getRoute()->setStaticPrefix($parameters['route_static_prefix']);
            }
        }
    }
}
