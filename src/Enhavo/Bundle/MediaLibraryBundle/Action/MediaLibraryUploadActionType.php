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
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUploadActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'cloud_upload',
            'label' => 'media_library.label.upload',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
            'model' => 'MediaLibraryUploadAction',
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_upload';
    }
}
