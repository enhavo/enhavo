<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\AppBundle\Action;


use Symfony\Component\OptionsResolver\OptionsResolver;

class Action
{
    /**
     * @var ActionTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(ActionTypeInterface $type, $options)
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

    public function getPermission()
    {
        return $this->type->getPermission($this->options);
    }

    public function isHidden()
    {
        return $this->type->isHidden($this->options);
    }
}