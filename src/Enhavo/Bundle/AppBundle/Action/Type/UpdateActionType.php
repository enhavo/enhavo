<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateActionType extends OpenActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'edit',
            'label' => 'label.edit',
            'translation_domain' => 'EnhavoAppBundle',
            'view_key' => 'edit-view',
            'target' => '_view'
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'update';
    }
}
