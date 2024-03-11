<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface FileNotFoundHandlerInterface
{
    public function handleFileNotFound(FileInterface $file, array $parameters = []): void;
}
