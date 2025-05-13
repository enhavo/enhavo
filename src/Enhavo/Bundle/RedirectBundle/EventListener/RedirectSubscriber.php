<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RedirectBundle\EventListener;

use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Enhavo\Bundle\RedirectBundle\Redirect\RedirectManager;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RedirectSubscriber implements EventSubscriberInterface
{
    /**
     * @var RedirectManager
     */
    private $redirectManager;

    public function __construct(RedirectManager $redirectManager)
    {
        $this->redirectManager = $redirectManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave',
        ];
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if ($resource instanceof RedirectInterface) {
            $this->redirectManager->update($resource);
        }
    }
}
