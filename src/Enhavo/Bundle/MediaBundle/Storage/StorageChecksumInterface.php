<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Storage;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface StorageChecksumInterface
{
    public function existsChecksum(string $checksum): bool;

    public function getContentByChecksum(string $checksum): ContentInterface;
}
