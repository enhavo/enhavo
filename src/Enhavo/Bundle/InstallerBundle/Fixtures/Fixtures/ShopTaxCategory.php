<?php
/**
 * ShopTaxCategory.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Sylius\Component\Taxation\Model\TaxCategory;
use Sylius\Component\Taxation\Model\TaxRate;

class ShopTaxCategory extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $taxCategory = new TaxCategory();
        $taxCategory->setCode($args['code']);
        $taxCategory->setName($args['name']);
        $taxCategory->setDescription($args['description']);
        $taxCategory->setCreatedAt(new \DateTime);
        return $taxCategory;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopTaxCategory';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 50;
    }
}