<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Cart\UserCartMerger;
use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserCartMerger
     */
    private $userCartMerger;

    public function __construct(UserCartMerger $userCartMerger)
    {
        $this->userCartMerger = $userCartMerger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::LOGIN => 'login',
        ];
    }

    public function login(GenericEvent $event)
    {
        $user = $event->getSubject();
        if($user instanceof UserInterface) {
            $this->userCartMerger->merge($user);
        }
    }
}