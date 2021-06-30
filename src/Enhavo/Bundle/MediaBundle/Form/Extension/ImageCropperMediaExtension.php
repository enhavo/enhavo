<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.09.17
 * Time: 16:24
 */

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageCropperMediaExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setNormalizer('extensions', function ($options, $value) {
            if(isset($options['formats']) && is_array($options['formats'])) {
                if(is_array($value)) {
                    return array_merge($value, [
                        'image_cropper' => [
                            'formats' => $options['formats']
                        ]
                    ]);
                }
                return [
                    'image_cropper' => [
                        'formats' => $options['formats']
                    ]
                ];
            }
            return $value;
        });

        $resolver->setDefaults([
            'formats' => null
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [MediaType::class];
    }
}
