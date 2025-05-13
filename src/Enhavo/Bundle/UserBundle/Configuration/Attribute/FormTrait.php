<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait FormTrait
{
    protected ?string $formClass = null;
    protected ?array $formOptions = null;

    public function getFormClass(): ?string
    {
        return $this->formClass;
    }

    public function setFormClass(?string $formClass): void
    {
        $this->formClass = $formClass;
    }

    public function getFormOptions(?array $options = null): ?array
    {
        if (is_array($options)) {
            return array_merge($options, $this->formOptions);
        }

        return $this->formOptions;
    }

    public function setFormOptions(?array $formOptions): void
    {
        $this->formOptions = $formOptions;
    }
}
