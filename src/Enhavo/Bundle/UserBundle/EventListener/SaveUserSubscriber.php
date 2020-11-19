<?php
/**
 * SaveUserSubscriber.php
 *
 * @since 25/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class SaveUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * SaveUserSubscriber constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }


    public static function getSubscribedEvents()
    {
        return [
            'enhavo_user.user.pre_update' => ['onSave', 1],
            'enhavo_user.user.pre_create' => ['onSave', 1],
        ];
    }

    public function onSave(GenericEvent $event)
    {
        /** @var $user UserInterface */
        $user = $event->getSubject();
        $this->userManager->updatePassword($user);
        $this->userManager->updateUsername($user);
    }
}
