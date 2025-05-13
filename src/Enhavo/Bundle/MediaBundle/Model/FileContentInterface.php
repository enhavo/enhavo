<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
