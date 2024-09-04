<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.01.18
 * Time: 16:02
 */

namespace Enhavo\Bundle\MediaBundle\Extension\Extension;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\MediaBundle\Extension\ExtensionInterface;
use Enhavo\Bundle\MediaBundle\Form\Type\ImageCropperType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageCropperExtension extends AbstractType implements ExtensionInterface
{
    public function renderExtension($options)
    {
        return '';
    }

    public function renderButton($options)
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, $options)
    {
//        $builder->add('image_cropper', ImageCropperType::class, [
//            'formats' => $options['formats']
//        ]);
    }

    public function getType()
    {
        return 'image_cropper';
    }
}
