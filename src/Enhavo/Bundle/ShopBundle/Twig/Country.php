<?php
/**
 * Country.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Twig;

use Symfony\Component\Intl\Intl;

class Country extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('country', array($this, 'getCountryName')),
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