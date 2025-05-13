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

class TextValue implements ValueAccessInterface
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $value;

    /** @var Setting|null */
    private $setting;

    public function __construct(?Setting $setting = null)
    {
        $this->setting = $setting;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    public function setSetting(?Setting $setting): void
    {
        $this->setting = $setting;
    }
}
