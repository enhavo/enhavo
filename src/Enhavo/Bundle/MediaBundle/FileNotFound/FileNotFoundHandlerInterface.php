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

use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

interface FileNotFoundHandlerInterface
{
    public function handleSave(FileInterface|FormatInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void;

    public function handleLoad(FileInterface|FormatInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void;

    public function handleDelete(FileInterface|FormatInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void;
}
