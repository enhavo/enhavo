<?php
/**
 * ShopShippingCategory.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;


use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Sylius\Component\Addressing\Model\Country;
use Sylius\Component\Shipping\Model\ShippingCategory;

class ShopShippingCategory extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $shippingCategory = new ShippingCategory();
        $shippingCategory->setCode($args['code']);
        $shippingCategory->setName($args['name']);
        $shippingCategory->setDescription($args['description']);
        return $shippingCategory;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopShippingCategory';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 50;
    }
}