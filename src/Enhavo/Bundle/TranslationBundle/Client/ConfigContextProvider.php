<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

use Enhavo\Bundle\MediaBundle\Factory\FileFactory;

class ConfigContextProvider implements ContextProviderInterface
{
    public function __construct(
        private readonly ?string $text,
        private readonly array $files = [],
        private readonly string $projectDir,
        private readonly FileFactory $fileFactory,
    )
    {
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getFiles(): array
    {
        $files = [];
        foreach ($this->files as $file) {
            $files[] = $this->fileFactory->createFromPath($this->projectDir . '/' . $file);
        }
        return $files;
    }
}
