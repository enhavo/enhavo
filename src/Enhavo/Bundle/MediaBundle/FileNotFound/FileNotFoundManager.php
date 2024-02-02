<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class FileNotFoundManager
{
    public function __construct(
        private TypeCollector $strategyCollector,
        private string $strategy,
        private array $strategyParameters,
    ) {}

    public function handleFileNotFound(FileInterface $file): void
    {
        /** @var FileNotFoundStrategyInterface $strategy */
        $strategy = $this->strategyCollector->getType($this->strategy);
        $strategy->handleFileNotFound($file, $this->strategyParameters);
    }
}
