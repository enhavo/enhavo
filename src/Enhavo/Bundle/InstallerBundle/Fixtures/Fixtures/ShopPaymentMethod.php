<?php
/**
 * ShopPaymentMethod.php
 *
 * @since 23/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;


use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Sylius\Component\Payment\Model\PaymentMethod;
use Sylius\Component\Payment\Model\PaymentMethodTranslation;

class ShopPaymentMethod extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->setCode($args['code']);
        $paymentMethod->setGateway($args['gateway']);
        $paymentMethod->setEnabled(1);
        $paymentMethod->setCreatedAt(new \DateTime());

        foreach($args['name'] as $locale => $name) {
            $paymentMethodTranslation = new PaymentMethodTranslation();
            $paymentMethodTranslation->setName($name);
            $paymentMethodTranslation->setLocale($locale);
            $paymentMethodTranslation->setTranslatable($paymentMethod);
            $this->container->get('doctrine.orm.entity_manager')->persist($paymentMethodTranslation);
        }

        return $paymentMethod;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'ShopPaymentMethod';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 50;
    }
}