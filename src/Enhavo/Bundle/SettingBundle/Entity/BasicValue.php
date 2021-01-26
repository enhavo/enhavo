<?php

namespace Enhavo\Bundle\SettingBundle\Entity;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class BasicValue implements ValueAccessInterface
{
    const TYPE_VARCHAR = 'varchar';
    const TYPE_FLOAT = 'float';
    const TYPE_INT = 'int';
    const TYPE_BOOLEAN = 'boolean';

    /** @var integer|null */
    private $id;

    /** @var string */
    private $type;

    /** @var string|null */
    private $varchar;

    /** @var float|null */
    private $float;

    /** @var int|null */
    private $int;

    /** @var boolean|null */
    private $boolean;

    /** @var Setting|null */
    private $setting;

    public function __construct($type = self::TYPE_VARCHAR, Setting $setting = null)
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
        $this->varchar = null;
        $this->float = null;
        $this->int = null;
        $this->boolean = null;

        if ($this->type === self::TYPE_VARCHAR) {
            $this->varchar = (string)$value;
        } elseif ($this->type === self::TYPE_FLOAT) {
            $this->float = (float)$value;
        } elseif ($this->type === self::TYPE_INT) {
            $this->int = (int)$value;
        } elseif ($this->type === self::TYPE_BOOLEAN) {
            $this->boolean = (boolean)$value;
        }
    }

    public function getValue()
    {
        if ($this->type === self::TYPE_VARCHAR) {
            return $this->varchar;
        } elseif ($this->type === self::TYPE_FLOAT) {
            return $this->float;
        } elseif ($this->type === self::TYPE_INT) {
            return $this->int;
        } elseif ($this->type === self::TYPE_BOOLEAN) {
            return $this->boolean;
        }
    }
}
