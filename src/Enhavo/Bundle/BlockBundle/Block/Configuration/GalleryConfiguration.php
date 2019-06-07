<?php
/**
 * GalleryConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Model\Block\GalleryBlock;
use Enhavo\Bundle\BlockBundle\Factory\GalleryBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\GalleryBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => GalleryBlock::class,
            'parent' => GalleryBlock::class,
            'form' => GalleryBlockType::class,
            'factory' => GalleryBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:Gallery',
            'template' => 'EnhavoBlockBundle:Block:gallery.html.twig',
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