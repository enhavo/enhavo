<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-05
 * Time: 02:14
 */

namespace Enhavo\Bundle\AppBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Widget
{
    /**
     * @var WidgetTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(WidgetTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function createViewData($resource = null)
    {
        return $this->type->createViewData($this->options, $resource);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate($this->options);
    }
}
