<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Batch\Type;

use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibrarySelectBatchType extends AbstractBatchType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'MediaLibrarySelectBatch',
            'label' => 'media_library.label.select',
            'translation_domain' => 'EnhavoMediaLibraryBundle',
        ]);
    }
}
