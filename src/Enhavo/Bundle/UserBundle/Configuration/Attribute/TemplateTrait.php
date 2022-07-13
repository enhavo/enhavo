<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait TemplateTrait
{
    private ?string $template = null;

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }
}
