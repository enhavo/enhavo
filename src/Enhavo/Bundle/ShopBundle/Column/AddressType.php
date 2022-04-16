<?php

namespace Enhavo\Bundle\ShopBundle\Column;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $property = $this->getProperty($resource, $options['property']);
        if ($property instanceof AddressInterface) {
            return sprintf('%s %s', $property->getFirstName(), $property->getLastName());
        }

        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-text',
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'shop_address';
    }
}
