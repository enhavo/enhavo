<?php
/**
 * PictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\PictureBlock;
use Enhavo\Bundle\BlockBundle\Factory\PictureBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\PictureBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureConfiguration extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => PictureBlock::class,
            'parent' => PictureBlock::class,
            'form' => PictureBlockType::class,
            'factory' => PictureBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:Picture',
            'template' => 'EnhavoBlockBundle:Block:picture.html.twig',
            'label' => 'picture.label.picture',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'picture';
    }
}
