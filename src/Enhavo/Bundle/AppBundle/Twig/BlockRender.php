<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Block\BlockFactory;

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
        $container = $this->factory->getContainer();
        $securityContext = $container->get('security.context');
        $currentUser = $securityContext->getToken()->getUser();
        $upperUpdateRoute = 'ROLE_'.strtoupper($parameters['update_route']);
        if(!in_array($upperUpdateRoute, $currentUser->getRoles())) {
            unset($parameters['update_route']);
        }
        $block = $this->factory->create($type);
        return $block->render($parameters);
    }

    public function getName()
    {
        return 'block_render';
    }
} 