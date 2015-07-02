<?php

namespace Enhavo\Bundle\AdminBundle\Twig;

use Enhavo\Bundle\AdminBundle\Block\BlockFactory;

class BlockRender extends \Twig_Extension
{
    protected $factory;

    public function __construct(BlockFactory $resolver)
    {
        $this->factory = $resolver;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('block_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render($type, $parameters)
    {
        $block = $this->factory->create($type);
        return $block->render($parameters);
    }

    public function getName()
    {
        return 'block_render';
    }
} 