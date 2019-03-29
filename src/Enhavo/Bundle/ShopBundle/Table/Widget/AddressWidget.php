<?php

namespace Enhavo\Bundle\ShopBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressWidget extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $address = $this->getProperty($resource, $options['property']);
        if($address instanceof AddressInterface) {
            return sprintf('%s %s', $address->getFirstName(), $address->getLastName());
        }
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
        $resolver->setRequired(['property']);
    }
    
    public function getType()
    {
        return 'shop_address';
    }
}
