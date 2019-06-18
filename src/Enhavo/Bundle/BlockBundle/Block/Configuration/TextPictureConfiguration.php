<?php
/**
 * TextPictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Model\Block\TextPictureBlock;
use Enhavo\Bundle\BlockBundle\Factory\TextPictureBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\TextPictureBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextPictureBlock::class,
            'parent' => TextPictureBlock::class,
            'form' => TextPictureBlockType::class,
            'factory' => TextPictureBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:TextPicture',
            'template' => 'EnhavoBlockBundle:Block:text_picture.html.twig',
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