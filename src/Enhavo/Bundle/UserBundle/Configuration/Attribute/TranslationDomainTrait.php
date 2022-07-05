<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait TranslationDomainTrait
{
    private ?string $translationDomain = null;

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }
}
