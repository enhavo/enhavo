<?php
/**
 * ShopProduct.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\ShopBundle\Entity\Product;

class ShopProduct extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $product = new Product();
        $product->setCode($args['code']);
        $product->setTitle($args['title']);
        $product->setPrice($args['price']);

        $product->setPicture($this->createImage($args['picture']));
        $product->setRoute($this->createRoute($args['url'], $product));
        $product->setTaxRate($this->getTaxRate($args['taxRate']));

        $product->setCreatedAt(new \DateTime());
        $product->setEnabled(true);

        $this->translate($product);

        return $product;
    }

    public function getTaxRate($code)
    {
        $repository = $this->container->get('sylius.repository.tax_rate');
        return $repository->findOneBy(['code' => $code]);
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopProduct';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 70;
    }
}