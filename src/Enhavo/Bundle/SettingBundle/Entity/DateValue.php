<?php

namespace Enhavo\Bundle\SettingBundle\Entity;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class DateValue implements ValueAccessInterface
{
    const TYPE_TIME = 'time';
    const TYPE_DATETIME = 'datetime';
    const TYPE_DATE = 'date';

    /** @var integer|null */
    private $id;

    /** @var string */
    private $type;

    /** @var \DateTime|null */
    private $value;

    /** @var Setting|null */
    private $setting;

    public function __construct($type = self::TYPE_DATETIME, Setting $setting = null)
    {
        $this->type = $type;
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

    public function setValue($value)
    {
        if ($value === null) {
            $this->value = null;
            return;
        }

        if (!$value instanceof \DateTime) {
            throw new \InvalidArgumentException();
        }

        if ($this->type === self::TYPE_TIME) {
            $value->setDate(1970,1,1);
        } elseif ($this->type === self::TYPE_DATE) {
            $value->setTime(0, 0, 0);
        }

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
