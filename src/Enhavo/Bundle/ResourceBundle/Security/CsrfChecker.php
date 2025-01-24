<?php

namespace Enhavo\Bundle\ResourceBundle\Security;

class CsrfChecker
{
    public function __construct(
        private ?bool $enabled
    )
    {
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
