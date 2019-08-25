<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 00:17
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Translation
{
    /**
     * @var TranslationTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $property;

    public function __construct(TranslationTypeInterface $type, $options, $data, $property)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->data = $data;
        $this->property = $property;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function getFormType()
    {
        return $this->type->getFormType($this->options);
    }

    public function getTranslation($locale)
    {
        return $this->type->getTranslation($this->options, $this->data, $this->property, $locale);
    }

    public function setTranslation($locale, $value)
    {
        return $this->type->setTranslation($this->options, $this->data, $this->property, $locale, $value);
    }
}
