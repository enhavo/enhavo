<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\FileType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component_model' => 'MediaItemForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }
}
