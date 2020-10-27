<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class UserEvent extends Event
{
    public const TYPE_CREATED = 'enhavo_user.user.created';
    public const TYPE_ACTIVATED = 'enhavo_user.user.activated';
    public const TYPE_REGISTRATION_CONFIRMED = 'enhavo_user.user.registration_confirmed';
    public const TYPE_PASSWORD_CHANGED = 'enhavo_user.user.password_changed';
    public const TYPE_PASSWORD_RESET_REQUESTED = 'enhavo_user.user.password_reset_requested';

    /** @var string */
    protected $type;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * UserEvent constructor.
     * @param string $type
     * @param UserInterface $user
     */
    public function __construct(string $type, UserInterface $user)
    {
        $this->type = $type;
        $this->user = $user;
    }


    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


}
