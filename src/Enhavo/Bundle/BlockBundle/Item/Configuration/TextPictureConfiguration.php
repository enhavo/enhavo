<?php
/**
 * TextPictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\TextPictureItem;
use Enhavo\Bundle\GridBundle\Factory\TextPictureItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\TextPictureItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => TextPictureItem::class,
            'parent' => TextPictureItem::class,
            'form' => TextPictureItemType::class,
            'factory' => TextPictureItemFactory::class,
            'repository' => 'EnhavoGridBundle:TextPicture',
            'template' => 'EnhavoGridBundle:Item:text_picture.html.twig',
            'label' => 'textPicture.label.textPicture',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'text_picture';
    }
}