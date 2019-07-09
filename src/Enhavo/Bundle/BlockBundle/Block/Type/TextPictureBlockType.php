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
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextPictureBlock::class,
            'parent' => TextPictureBlock::class,
            'form' => TextPictureBlockFormType::class,
            'factory' => TextPictureBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:TextPictureBlock',
            'template' => 'theme/block/text-picture.html.twig',
            'label' => 'textPicture.label.textPicture',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'text_picture';
    }
}
