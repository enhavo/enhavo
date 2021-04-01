<?php
/**
 * ShopTaxRate.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Sylius\Component\Taxation\Model\TaxRate;

class ShopTaxRate extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $taxRate = new TaxRate();
        $taxRate->setCode($args['code']);
        $taxRate->setName($args['name']);
        $taxRate->setAmount($args['amount']);
        $taxRate->setIncludedInPrice($args['includedInPrice']);
        $taxRate->setCalculator($args['calculator']);
        $taxRate->setCreatedAt(new \DateTime);
        $taxRate->setCategory($this->getTaxCategory($args['category']));
        return $taxRate;
    }

    public function getTaxCategory($name)
    {
        $repository = $this->container->get('sylius.repository.tax_category');
        return $repository->findOneBy(['name' => $name]);
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopTaxRate';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 60;
    }
}
