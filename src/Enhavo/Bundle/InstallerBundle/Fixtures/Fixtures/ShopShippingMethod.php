<?php
/**
 * ShopShippingMethod.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Sylius\Component\Shipping\Model\ShippingMethod;
use Sylius\Component\Shipping\Model\ShippingMethodTranslation;

class ShopShippingMethod extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $shippingMethod = new ShippingMethod();
        $shippingMethod->setCode($args['code']);
        $shippingMethod->setCategory($this->getShippingCategory($args['category']));
        $shippingMethod->setCalculator($args['calculator']);
        $shippingMethod->setConfiguration($args['configuration']);
        $shippingMethod->setCategoryRequirement(0);
        $shippingMethod->setEnabled(true);
        $shippingMethod->setCreatedAt(new \DateTime);

        foreach($args['name'] as $locale => $name) {
            $shippingMethodTranslation = new ShippingMethodTranslation();
            $shippingMethodTranslation->setName($name);
            $shippingMethodTranslation->setLocale($locale);
            $shippingMethodTranslation->setTranslatable($shippingMethod);
            $this->container->get('doctrine.orm.entity_manager')->persist($shippingMethodTranslation);
        }

        return $shippingMethod;
    }

    public function getShippingCategory($name)
    {
        $repository = $this->container->get('sylius.repository.shipping_category');
        return $repository->findOneBy(['name' => $name]);
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopShippingMethod';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 60;
    }
}