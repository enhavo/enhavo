<?php

namespace Enhavo\Bundle\SettingBundle\Entity;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class Setting
{
    /** @var integer|null */
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

    /** @var integer|null */
    private $valueId;

    /** @var string|null */
    private $valueClass;

    /** @var int|null */
    private $position;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     */
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

    /**
     * @param object|null $value
     */
    public function setValue(?object $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getValueId(): ?int
    {
        return $this->valueId;
    }

    /**
     * @param int|null $valueId
     */
    public function setValueId(?int $valueId): void
    {
        $this->valueId = $valueId;
    }

    /**
     * @return string|null
     */
    public function getValueClass(): ?string
    {
        return $this->valueClass;
    }

    /**
     * @param string|null $valueClass
     */
    public function setValueClass(?string $valueClass): void
    {
        $this->valueClass = $valueClass;
    }

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

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     */
    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    public function __clone()
    {
        $this->id = null;
    }
}
