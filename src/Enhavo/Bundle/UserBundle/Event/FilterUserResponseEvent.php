<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterUserResponseEvent extends UserEvent
{
    private $response;

    /**
     * FilterUserResponseEvent constructor.
     *
     * @param UserInterface $user
     * @param Request       $request
     * @param Response      $response
     */
    public function __construct(UserInterface $user, Request $request, Response $response)
    {
        parent::__construct($user, $request);
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets a new response object.
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
