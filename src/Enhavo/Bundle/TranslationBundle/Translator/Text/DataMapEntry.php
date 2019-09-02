<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 20:28
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Text;

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
     * @param string|null $property
     * @param string|null $locale
     * @param mixed|null $data
     */
    public function __construct(?string $property, ?string $locale, $data)
    {
        $this->property = $property;
        $this->locale = $locale;
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getProperty(): ?string
    {
        return $this->property;
    }

    /**
     * @param string|null $property
     */
    public function setProperty(?string $property): void
    {
        $this->property = $property;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     */
    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
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
