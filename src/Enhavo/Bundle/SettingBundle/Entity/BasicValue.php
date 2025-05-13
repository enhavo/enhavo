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

class BasicValue implements ValueAccessInterface
{
    public const TYPE_VARCHAR = 'varchar';
    public const TYPE_FLOAT = 'float';
    public const TYPE_INT = 'int';
    public const TYPE_BOOLEAN = 'boolean';

    /** @var int|null */
    private $id;

    /** @var string */
    private $type;

    /** @var string|null */
    private $varchar;

    /** @var float|null */
    private $float;

    /** @var int|null */
    private $int;

    /** @var bool|null */
    private $boolean;

    /** @var Setting|null */
    private $setting;

    public function __construct($type = self::TYPE_VARCHAR, ?Setting $setting = null)
    {
        $this->type = $type;
        $this->setting = $setting;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

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

        if (self::TYPE_VARCHAR === $this->type) {
            $this->varchar = (string) $value;
        } elseif (self::TYPE_FLOAT === $this->type) {
            $this->float = (float) $value;
        } elseif (self::TYPE_INT === $this->type) {
            $this->int = (int) $value;
        } elseif (self::TYPE_BOOLEAN === $this->type) {
            $this->boolean = (bool) $value;
        }
    }

    public function getValue()
    {
        if (self::TYPE_VARCHAR === $this->type) {
            return $this->varchar;
        } elseif (self::TYPE_FLOAT === $this->type) {
            return $this->float;
        } elseif (self::TYPE_INT === $this->type) {
            return $this->int;
        } elseif (self::TYPE_BOOLEAN === $this->type) {
            return $this->boolean;
        }
    }
}
