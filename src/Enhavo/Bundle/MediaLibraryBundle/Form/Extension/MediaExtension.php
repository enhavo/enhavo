<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.09.17
 * Time: 16:24
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'extensions' => [
                'media_library' => []
            ]
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [MediaType::class];
    }
}
