<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Action\Type\SaveActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUpdateActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route' => 'enhavo_media_library_admin_api_item_meta',
            'label' => 'media_library.label.update',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'icon' => 'system_update_alt',
            'confirm' => true,
            'confirm_message' => 'media_library.message.update.confirm',
            'confirm_label_ok' => 'media_library.label.update',
            'confirm_label_cancel' => 'media_library.label.cancel',
            'model' => 'MediaLibraryUpdateAction',
        ]);
    }

    public static function getParentType(): string
    {
        return SaveActionType::class;
    }

    public static function getName(): ?string
    {
        return 'media_library_update';
    }
}
