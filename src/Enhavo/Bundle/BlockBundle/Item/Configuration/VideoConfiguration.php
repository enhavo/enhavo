<?php
/**
 * VideoConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Item\VideoItem;
use Enhavo\Bundle\GridBundle\Factory\VideoItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\VideoItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => VideoItem::class,
            'parent' => VideoItem::class,
            'form' => VideoItemType::class,
            'factory' => VideoItemFactory::class,
            'repository' => 'EnhavoGridBundle:Video',
            'template' => 'EnhavoGridBundle:Item:video.html.twig',
            'label' => 'video.label.video',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return 'video';
    }
}