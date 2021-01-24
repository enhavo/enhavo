<?php

namespace Enhavo\Bundle\SettingBundle\Entity;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class TextValue implements ValueAccessInterface
{
    /** @var integer|null */
    private $id;

    /** @var string|null */
    private $value;

    /** @var Setting|null */
    private $setting;

    public function __construct(Setting $setting = null)
    {
        $this->setting = $setting;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value ;
    }

    /**
     * @return Setting|null
     */
    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    /**
     * @param Setting|null $setting
     */
    public function setSetting(?Setting $setting): void
    {
        $this->setting = $setting;
    }
}
