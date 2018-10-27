<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.10.18
 * Time: 22:40
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Filter
{
    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $name;

    /**
     * Filter constructor.
     *
     * @param FilterInterface $filter
     * @param $options
     * @param $name
     */
    public function __construct(FilterInterface $filter, $options, $name)
    {
        $this->filter = $filter;

        $resolver = new OptionsResolver();
        $this->filter->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->name = $name;
    }

    public function render()
    {
        return $this->filter->render($this->options, $this->getName());
    }

    public function buildQuery(FilterQuery $query, $value)
    {
        return $this->filter->buildQuery($query, $this->options, $value);
    }

    public function getPermission()
    {
        return $this->filter->getPermission($this->options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}