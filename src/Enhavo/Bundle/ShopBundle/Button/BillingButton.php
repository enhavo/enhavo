<?php

/**
 * BillingButton.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class BillingButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        return $this->renderTemplate('EnhavoShopBundle:Admin:Button/billing.html.twig', [
            'type' => 'shop_billing',
            'icon' => $this->getOption('icon', $options, 'text-document'),
            'display' =>  $this->getOption('display', $options, true),
            'role' => $this->getOption('role', $options, null),
            'label' => $this->getOption('label', $options, 'order.label.billing'),
            'translationDomain' => $this->getOption('translationDomain', $options, 'EnhavoShopBundle')
        ]);
    }

    public function getType()
    {
        return 'shop_billing';
    }
}