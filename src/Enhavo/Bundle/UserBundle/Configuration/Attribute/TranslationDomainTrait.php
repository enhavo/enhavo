<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait TranslationDomainTrait
{
    /** @var ?string */
    private $translationDomain;

    /**
     * @return string|null
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * @param string|null $translationDomain
     */
    public function setTranslationDomain(?string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }
}
