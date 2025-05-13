<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Checksum;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

class Sha256ChecksumGenerator implements ChecksumGeneratorInterface
{
    public function getChecksum(ContentInterface $content): string
    {
        return hash('sha256', $content->getContent());
    }
}
