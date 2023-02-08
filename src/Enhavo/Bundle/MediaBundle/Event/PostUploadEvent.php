<?php

namespace Enhavo\Bundle\MediaBundle\Event;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Contracts\EventDispatcher\Event;

class PostUploadEvent extends Event
{
    const DEFAULT_EVENT_NAME = 'enhavo_media.post_upload';

    private FileInterface $subject;

    public function __construct(FileInterface $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return FileInterface
     */
    public function getSubject(): FileInterface
    {
        return $this->subject;
    }
}
