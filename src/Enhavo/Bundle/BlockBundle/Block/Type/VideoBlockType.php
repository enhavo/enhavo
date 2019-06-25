<?php
/**
 * VideoConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\VideoBlock;
use Enhavo\Bundle\BlockBundle\Factory\VideoBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\VideoBlockType as VideoBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => VideoBlock::class,
            'parent' => VideoBlock::class,
            'form' => VideoBlockFormType::class,
            'factory' => VideoBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:Video',
            'template' => 'EnhavoBlockBundle:Block:video.html.twig',
            'label' => 'video.label.video',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'video';
    }
}
