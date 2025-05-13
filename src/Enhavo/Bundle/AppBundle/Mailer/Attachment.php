<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Mailer;

class Attachment
{
    public function __construct(
        private $file,
        private ?string $name = null,
        private ?string $mimetype = null,
    ) {
    }

    public function getFile(): mixed
    {
        return $this->file;
    }

    public function getName(): ?string
    {
        if (null === $this->name) {
        }

        return $this->name;
    }

    public function getMimetype(): ?string
    {
        return $this->mimetype;
    }
}
