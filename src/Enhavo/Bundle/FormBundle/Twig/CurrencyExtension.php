<?php
/**
 * TwigFunction.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class CurrencyExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new TwigFunction('currency', array($this, 'getCurrency'), array('is_safe' => array('html')))
        );
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('currency', array($this, 'getCurrency'), array('is_safe' => array('html')))
        );
    }

    public function getCurrency($value, $currency = 'Euro', $position = 'right')
    {
        $currencyFormatter = $this->container->get(CurrencyFormatter::class);
        return $currencyFormatter->getCurrency($value, $currency, $position);
    }
}
