<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait TemplateTrait
{
    private ?string $template;

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }
}
