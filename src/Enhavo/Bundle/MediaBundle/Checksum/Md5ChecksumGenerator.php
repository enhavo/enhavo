<?php

namespace Enhavo\Bundle\MediaBundle\Checksum;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

class Md5ChecksumGenerator implements ChecksumGeneratorInterface
{
    public function getChecksum(ContentInterface $content): string
    {
        return md5($content->getContent());
    }
}
