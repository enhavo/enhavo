<?php
/**
 * ShopCountry.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;


use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Sylius\Component\Addressing\Model\Country;

class ShopCountry extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $country = new Country();
        $country->setCode($args['code']);
        $country->setEnabled(true);
        return $country;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopCountry';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 50;
    }
}