<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 12.12.16
 * Time: 15:51
 */

namespace Enhavo\Bundle\ShopBundle\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class PackingSlipButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        return $this->renderTemplate('EnhavoShopBundle:Admin:Button/billing.html.twig', [
            'type' => 'shop_packing_slip',
            'icon' => $this->getOption('icon', $options, 'truck'),
            'display' =>  $this->getOption('display', $options, true),
            'role' => $this->getOption('role', $options, null),
            'label' => $this->getOption('label', $options, 'order.label.packing_slip'),
            'translationDomain' => $this->getOption('translationDomain', $options, 'EnhavoShopBundle')
        ]);
    }

    public function getType()
    {
        return 'shop_packing_slip';
    }
}