<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Event;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Contracts\EventDispatcher\Event;

class PostUploadEvent extends Event
{
    public const DEFAULT_EVENT_NAME = 'enhavo_media.post_upload';

    private FileInterface $subject;

    public function __construct(FileInterface $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject(): FileInterface
    {
        return $this->subject;
    }
}
