<?php
/**
 * TwigFunction.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Twig;

class Currency extends \Twig_Extension
{
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
        $string = (string)$value;
        $string = str_pad($string, 3, '0', STR_PAD_LEFT);
        $length = strlen($string);
        $right = substr($string, $length - 2, 2 );
        $left = substr($string, 0, $length - 2);
        return sprintf('%s,%s &euro;', $left, $right);
    }

    public function getName()
    {
        return 'enhavo_currency';
    }
}