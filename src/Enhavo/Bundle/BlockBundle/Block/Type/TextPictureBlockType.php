<?php
/**
 * TextPictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Enhavo\Bundle\BlockBundle\Factory\TextPictureBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextPictureBlockType as TextPictureBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => TextPictureBlock::class,
            'form' => TextPictureBlockFormType::class,
            'factory' => TextPictureBlockFactory::class,
            'template' => 'theme/block/text-picture.html.twig',
            'label' => 'textPicture.label.textPicture',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content', 'simple']
        ]);
    }

    public static function getName(): ?string
    {
        return 'text_picture';
    }
}
