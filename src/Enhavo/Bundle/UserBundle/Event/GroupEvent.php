<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class GroupEvent extends Event
{
    /**
     * @var GroupInterface
     */
    private $group;

    /**
     * @var Request
     */
    private $request;

    /**
     * GroupEvent constructor.
     *
     * @param GroupInterface $group
     * @param Request        $request
     */
    public function __construct(GroupInterface $group, Request $request)
    {
        $this->group = $group;
        $this->request = $request;
    }

    /**
     * @return GroupInterface
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
