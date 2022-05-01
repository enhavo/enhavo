<?php

namespace Enhavo\Bundle\AppBundle\Mailer;

class Attachment
{
    public function __construct(
        private $file,
        private ?string $name = null,
        private ?string $mimetype = null,
    ) {}

    public function getFile(): mixed
    {
        return $this->file;
    }

    public function getName(): ?string
    {
        if ($this->name === null) {

        }
        return $this->name;
    }

    public function getMimetype(): ?string
    {
        return $this->mimetype;
    }
}
