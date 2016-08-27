<?php

namespace Enhavo\Bundle\ShopBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class ShopExtension extends \Twig_Extension
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('shop_product_list_render', array($this, 'renderProductList'), array('is_safe' => array('html'))),
        );
    }

    public function renderProductList($options = [])
    {
        return $this->container->get('enhavo_shop.render.product_list_renderer')->render($options);
    }

    public function getName()
    {
        return 'shop_extension';
    }
} 