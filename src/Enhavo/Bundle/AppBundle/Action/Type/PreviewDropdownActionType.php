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

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreviewDropdownActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'label.preview_frame',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
            'items' => [
                'preview' => [
                    'type' => 'preview',
                ],
                'preview_window' => [
                    'type' => 'preview_window',
                ],
            ],
        ]);
    }

    public static function getParentType(): ?string
    {
        return DropdownActionType::class;
    }

    public static function getName(): ?string
    {
        return 'preview_dropdown';
    }
}
