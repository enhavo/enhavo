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
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

interface StorageInterface
{
    /** @throws StorageException */
    public function deleteContent(FileInterface|FormatInterface $file): void;

    /** @throws StorageException */
    public function saveContent(FileInterface|FormatInterface $file): ContentInterface;

    /** @throws StorageException */
    public function getContent(FileInterface|FormatInterface $file): ContentInterface;
}
