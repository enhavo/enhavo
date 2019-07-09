<?php
/**
 * GalleryConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\GalleryBlock;
use Enhavo\Bundle\BlockBundle\Factory\GalleryBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\GalleryBlockType as GalleryBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => GalleryBlock::class,
            'parent' => GalleryBlock::class,
            'form' => GalleryBlockFormType::class,
            'factory' => GalleryBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:GalleryBlock',
            'template' => 'theme/block/gallery.html.twig',
            'label' => 'gallery.label.gallery',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'gallery';
    }
}
