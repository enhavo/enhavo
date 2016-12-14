<?php
/**
 * TwigFunction.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
class Currency extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('currency', array($this, 'getCurrency'), array('is_safe' => array('html')))
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('currency', array($this, 'getCurrency'), array('is_safe' => array('html')))
        );
    }

    public function getCurrency($value)
    {
        $currencyFormatter = $this->container->get('enhavo_app.formatter.currency_formatter');
        return $currencyFormatter->getCurrency($value);
    }

    public function getName()
    {
        return 'enhavo_currency';
    }
}