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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Uid\Uuid;

class PreviewWindowActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        $data->set('target', $options['target']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'PreviewWindowAction',
            'label' => 'label.preview_window',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'component' => 'action-preview',
            'target' => '_blank_'.Uuid::v4(),
        ]);
    }

    public static function getParentType(): ?string
    {
        return PreviewActionType::class;
    }

    public static function getName(): ?string
    {
        return 'preview_window';
    }
}
