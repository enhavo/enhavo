<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UserLoginEvent extends Event
{
    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * UserLoginEvent constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
