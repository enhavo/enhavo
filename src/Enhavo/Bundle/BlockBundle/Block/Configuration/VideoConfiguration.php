<?php
/**
 * VideoConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Model\Block\VideoBlock;
use Enhavo\Bundle\BlockBundle\Factory\VideoBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\VideoBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => VideoBlock::class,
            'parent' => VideoBlock::class,
            'form' => VideoBlockType::class,
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