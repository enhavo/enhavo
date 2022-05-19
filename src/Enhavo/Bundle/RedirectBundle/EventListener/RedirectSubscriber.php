<?php
/**
 * RedirectSubscriber.php
 *
 * @since 02/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\RedirectBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Enhavo\Bundle\RedirectBundle\Redirect\RedirectManager;
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
        return array(
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave'
        );
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof RedirectInterface) {
            $this->redirectManager->update($resource);
        }
    }
}
