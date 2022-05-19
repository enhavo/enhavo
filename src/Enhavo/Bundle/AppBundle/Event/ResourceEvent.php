<?php

namespace Enhavo\Bundle\AppBundle\Event;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ResourceEvent extends Event
{
    private ResourceInterface $subject;

    public function __construct(ResourceInterface $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject(): ResourceInterface
    {
        return $this->subject;
    }
}
