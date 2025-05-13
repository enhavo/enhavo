<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\SaveActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoSaveActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('timeout', $options['timeout']);
        $data->set('iconInactive', $options['icon_inactive']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.auto_save',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'sync',
            'icon_inactive' => 'sync_disabled',
            'route' => null,
            'route_parameters' => [],
            'model' => 'AutoSaveAction',
            'component' => 'action-auto-save',
            'timeout' => 5,
        ]);
    }

    public static function getParentType(): ?string
    {
        return SaveActionType::class;
    }

    public static function getName(): ?string
    {
        return 'auto_save';
    }
}
