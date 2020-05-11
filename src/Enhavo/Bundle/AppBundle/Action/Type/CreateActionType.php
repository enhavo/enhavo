<?php

/**
 * CreateAction.php
 *
 * @since 30/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateActionType extends OpenActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'add_circle_outline',
            'label' => 'label.create',
            'translation_domain' => 'EnhavoAppBundle',
            'view_key' => 'edit-view',
            'target' => '_view'
        ]);
    }

    public function getType()
    {
        return 'create';
    }
}
