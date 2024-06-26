<?php

namespace Enhavo\Bundle\ShopBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class AddressType extends AbstractColumnType
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $property = $propertyAccessor->getValue($resource, $options['property']);
        if ($property instanceof AddressInterface) {
            $data->set('value', sprintf('%s %s', $property->getFirstName(), $property->getLastName()));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'shop_address';
    }
}
