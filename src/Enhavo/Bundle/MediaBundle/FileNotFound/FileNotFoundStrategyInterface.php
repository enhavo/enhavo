<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface FileNotFoundStrategyInterface extends TypeInterface
{
    public function handleFileNotFound(FileInterface $file, ?array $strategyParameters): void;
}
