<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 15:24
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Widget
{
    /**
     * @var ToolbarWidgetTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(ToolbarWidgetTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function createViewData()
    {
        return $this->type->createViewData($this->options);
    }

    public function getPermission()
    {
        return $this->type->getPermission($this->options);
    }

    public function isHidden()
    {
        return $this->type->isHidden($this->options);
    }
}
