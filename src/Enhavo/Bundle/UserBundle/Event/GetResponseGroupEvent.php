<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Symfony\Component\HttpFoundation\Response;

class GetResponseGroupEvent extends GroupEvent
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
