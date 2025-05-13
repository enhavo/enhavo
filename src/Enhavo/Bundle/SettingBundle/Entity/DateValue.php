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

class DateValue implements ValueAccessInterface
{
    public const TYPE_TIME = 'time';
    public const TYPE_DATETIME = 'datetime';
    public const TYPE_DATE = 'date';

    /** @var int|null */
    private $id;

    /** @var string */
    private $type;

    /** @var \DateTime|null */
    private $value;

    /** @var Setting|null */
    private $setting;

    public function __construct($type = self::TYPE_DATETIME, ?Setting $setting = null)
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
        if (null === $value) {
            $this->value = null;

            return;
        }

        if (!$value instanceof \DateTime) {
            throw new \InvalidArgumentException();
        }

        if (self::TYPE_TIME === $this->type) {
            $value->setDate(1970, 1, 1);
        } elseif (self::TYPE_DATE === $this->type) {
            $value->setTime(0, 0, 0);
        }

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
