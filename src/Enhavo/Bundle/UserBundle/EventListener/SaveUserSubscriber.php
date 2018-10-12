<?php
/**
 * SaveUserSubscriber.php
 *
 * @since 25/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use FOS\UserBundle\Model\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use FOS\UserBundle\Model\UserInterface;

class SaveUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var $userManager
     */
    protected $userManager;

    public function __construct(UserManager $userManger)
    {
        $this->userManager = $userManger;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_user.user.pre_update' => array('onSave', 1),
            'enhavo_user.user.pre_create' => array('onSave', 1),
        );
    }

    public function onSave(GenericEvent $event)
    {
        /** @var $user UserInterface */
        $user = $event->getSubject();
        if($user->getPlainPassword()) {
            $this->userManager->updatePassword($user);
        }

        $this->userManager->updateCanonicalFields($user);
    }
}