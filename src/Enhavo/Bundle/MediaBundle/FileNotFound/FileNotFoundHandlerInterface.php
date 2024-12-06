<?php

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
