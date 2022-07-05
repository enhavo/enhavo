<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    public const TYPE_CREATED = 'enhavo_user.user.created';
    public const TYPE_ACTIVATED = 'enhavo_user.user.activated';
    public const TYPE_REGISTRATION_CONFIRMED = 'enhavo_user.user.registration_confirmed';
    public const TYPE_PASSWORD_CHANGED = 'enhavo_user.user.password_changed';
    public const TYPE_EMAIl_CHANGED = 'enhavo_user.user.email_changed';
    public const TYPE_PASSWORD_RESET_REQUESTED = 'enhavo_user.user.password_reset_requested';

    public function __construct(
        protected string $type,
        protected UserInterface $user,
    )
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getType(): string
    {
        return $this->type;
    }


}
