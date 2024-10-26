<?php

namespace Enhavo\Bundle\MediaBundle\Checksum;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

class Sha256ChecksumGenerator implements ChecksumGeneratorInterface
{
    public function getChecksum(ContentInterface $content): string
    {
        return hash('sha256', $content->getContent());
    }
}
