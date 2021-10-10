<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait TemplateTrait
{
    /** @var ?string */
    private $template;

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }
}
