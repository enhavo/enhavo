<?php
/**
 * Country.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Twig;

use Symfony\Component\Intl\Intl;
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
        return Intl::getRegionBundle()->getCountryName($countryCode);
    }

    public function getName()
    {
        return 'enhavo_country';
    }
}
