<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    public function setParameters(array $parameters)
    {
        // do nothing
    }

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        throw new FileNotFoundException($exception->getMessage(), $exception->getCode(), $exception);
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        throw new FileNotFoundException($exception->getMessage(), $exception->getCode(), $exception);
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        throw new FileNotFoundException($exception->getMessage(), $exception->getCode(), $exception);
    }
}
