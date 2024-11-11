<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    public function handleFileNotFound(FileInterface $file, array $parameters = []): void
    {
        throw new NotFoundHttpException(sprintf('File "%s" doesn\'t exist', $file->getBasename()));
    }
}
