<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface ContextProviderInterface
{
    public function getText(): ?string;

    /** @return FileInterface[] */
    public function getFiles(): array;
}
