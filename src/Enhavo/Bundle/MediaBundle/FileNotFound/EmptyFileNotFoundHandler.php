<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class EmptyFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        $file->setContent(new Content('', null));
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }
}
