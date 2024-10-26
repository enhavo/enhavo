<?php

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface FileContentInterface
{
    public function getFilename(): string;

    public function getBasename(): string;

    public function getExtension(): ?string;

    public function getMimeType(): string;

    public function getContent(): ContentInterface;

    public function getChecksum(): string;
}
