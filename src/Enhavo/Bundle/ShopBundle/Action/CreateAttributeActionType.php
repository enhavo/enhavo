<?php

namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAttributeActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'create-attribute-action',
            'icon' => 'add_circle_outline',
            'label' => 'label.create',
            'translation_domain' => 'EnhavoAppBundle',
            'frame_key' => 'edit-view',
            'target' => '_frame',
            'route' => null
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_create_attribute';
    }
}
