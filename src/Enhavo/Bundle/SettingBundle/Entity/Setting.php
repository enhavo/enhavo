<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Entity;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class Setting
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $label;

    /** @var string|null */
    private $translationDomain;

    /** @var string|null */
    private $group;

    /** @var string|null */
    private $key;

    /** @var ValueAccessInterface|null */
    private $value;

    /** @var int|null */
    private $valueId;

    /** @var string|null */
    private $valueClass;

    /** @var int|null */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return ValueAccessInterface|null
     */
    public function getValue(): ?object
    {
        return $this->value;
    }

    public function setValue(?object $value): void
    {
        $this->value = $value;
    }

    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    public function setValueId(?int $valueId): void
    {
        $this->valueId = $valueId;
    }

    public function getValueClass(): ?string
    {
        return $this->valueClass;
    }

    public function setValueClass(?string $valueClass): void
    {
        $this->valueClass = $valueClass;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(?string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    public function __clone()
    {
        $this->id = null;
    }
}
