<?php

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface FileContentInterface
{
    /**
     * @return ?string
     */
    public function getFilename();

    /**
     * Get mimeType
     *
     * @return ?string
     */
    public function getMimeType();

    /**
     * @return ContentInterface
     */
    public function getContent();
}
