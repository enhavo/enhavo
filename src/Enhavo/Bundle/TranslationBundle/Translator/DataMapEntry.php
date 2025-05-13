<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

class DataMapEntry
{
    /**
     * @var string|null
     */
    private $property;

    /**
     * @var string|null
     */
    private $locale;

    /**
     * @var mixed|null
     */
    private $data;

    /**
     * DataMapEntry constructor.
     *
     * @param mixed|null $data
     */
    public function __construct(?string $property, ?string $locale, $data)
    {
        $this->property = $property;
        $this->locale = $locale;
        $this->data = $data;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed|null $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}
