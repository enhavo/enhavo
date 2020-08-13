<?php
/**
 * Country.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Twig;

use Symfony\Component\Intl\Countries;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Country extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('country', array($this, 'getCountryName')),
        );
    }

    public function getCountryName($countryCode)
    {
        return Countries::getName($countryCode);
    }

    public function getName()
    {
        return 'enhavo_country';
    }
}
