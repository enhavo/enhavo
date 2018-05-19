<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 14:33
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractItem implements ItemInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $name;

    /**
     * Item constructor.
     * @param ConfigurationInterface $configuration
     * @param string $name
     * @param $options
     */
    public function __construct(ConfigurationInterface $configuration, $name, $options)
    {
        $resolver = new OptionsResolver();
        $configuration->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->configuration = $configuration;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->configuration->getLabel($this->options);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->configuration->getType();
    }

    /**
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->configuration->getTranslationDomain($this->options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}