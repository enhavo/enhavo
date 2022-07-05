<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserLoginEvent extends Event
{
    public function __construct(
        protected UserInterface $user,
    )
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
