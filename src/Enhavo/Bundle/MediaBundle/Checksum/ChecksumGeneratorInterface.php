<?php

namespace Enhavo\Bundle\MediaBundle\Checksum;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface ChecksumGeneratorInterface
{
    public function getChecksum(ContentInterface $content): string;
}
