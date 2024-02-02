<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound\Strategy;

use Enhavo\Bundle\MediaBundle\FileNotFound\FileNotFoundStrategyInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionStrategy implements FileNotFoundStrategyInterface
{
    public function handleFileNotFound(FileInterface $file, ?array $strategyParameters): void
    {
        throw new NotFoundHttpException('File doesn\'t exist, please refresh format');
    }

    public function getType(): string
    {
        return 'exception';
    }
}
