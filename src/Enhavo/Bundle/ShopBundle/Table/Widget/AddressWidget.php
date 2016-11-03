<?php

namespace Enhavo\Bundle\ShopBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Sylius\Component\Addressing\Model\AddressInterface;

class AddressWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $address = $this->getProperty($item, $options['property']);
        if($address instanceof AddressInterface) {
            return sprintf('%s %s', $address->getFirstName(), $address->getLastName());
        }
        return '';
    }
    
    public function getType()
    {
        return 'shop_address';
    }
}