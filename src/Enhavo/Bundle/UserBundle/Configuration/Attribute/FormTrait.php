<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait FormTrait
{
    /** @var ?string */
    protected $formClass;

    /** @var ?array */
    protected $formOptions;

    /**
     * @return string|null
     */
    public function getFormClass(): ?string
    {
        return $this->formClass;
    }

    /**
     * @param string|null $formClass
     */
    public function setFormClass(?string $formClass): void
    {
        $this->formClass = $formClass;
    }

    /**
     * @return array|null
     */
    public function getFormOptions(array $options = null): ?array
    {
        if (is_array($options)) {
            return array_merge($options, $this->formOptions);
        }

        return $this->formOptions;
    }

    /**
     * @param array|null $formOptions
     */
    public function setFormOptions(?array $formOptions): void
    {
        $this->formOptions = $formOptions;
    }
}
