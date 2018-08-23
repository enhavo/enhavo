<?php
/**
 * GalleryConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\GalleryItem;
use Enhavo\Bundle\GridBundle\Factory\GalleryItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\GalleryItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => GalleryItem::class,
            'parent' => GalleryItem::class,
            'form' => GalleryItemType::class,
            'factory' => GalleryItemFactory::class,
            'repository' => 'EnhavoGridBundle:Gallery',
            'template' => 'EnhavoGridBundle:Item:gallery.html.twig',
            'label' => 'gallery.label.gallery',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'gallery';
    }
}