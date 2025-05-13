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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;

class MediaValue implements ValueAccessInterface
{
    /** @var int|null */
    private $id;

    /** @var bool */
    private $multiple;

    /** @var FileInterface|null */
    private $file;

    /** @var FileInterface[]|Collection|null */
    private $files;

    /** @var Setting|null */
    private $setting;

    public function __construct($multiple = false, ?Setting $setting = null)
    {
        $this->multiple = $multiple;
        $this->setting = $setting;
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setValue($value)
    {
        if ($this->multiple) {
            $this->files = $value;
        } else {
            $this->file = $value;
        }
    }

    public function getValue()
    {
        return $this->multiple ? $this->files : $this->file;
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
