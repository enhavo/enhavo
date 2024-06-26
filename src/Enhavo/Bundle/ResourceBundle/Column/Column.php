<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-03-28
 * Time: 15:31
 */

namespace Enhavo\Bundle\ResourceBundle\Column;


use Symfony\Component\OptionsResolver\OptionsResolver;

class Column
{
    /**
     * @var ColumnTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * Column constructor.
     * @param ColumnTypeInterface $type
     * @param $options
     */
    public function __construct(ColumnTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function createColumnViewData()
    {
        return $this->type->createColumnViewData($this->options);
    }

    public function createResourceViewData($resource)
    {
        return $this->type->createResourceViewData($this->options, $resource);
    }

    public function getPermission()
    {
        return isset($this->options['permission']) ? $this->options['permission'] : null;
    }
}
