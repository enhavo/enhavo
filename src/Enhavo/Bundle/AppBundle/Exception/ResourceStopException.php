<?php

namespace Enhavo\Bundle\AppBundle\Exception;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Response;

class ResourceStopException extends \Exception
{
    private ?Response $response;

    private ResourceControllerEvent $event;

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function getEvent(): ResourceControllerEvent
    {
        return $this->event;
    }

    public function setEvent(ResourceControllerEvent $event): void
    {
        $this->event = $event;
    }
}
