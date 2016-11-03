<?php
/**
 * VideoConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\Video;
use Enhavo\Bundle\GridBundle\Factory\VideoFactory;
use Enhavo\Bundle\GridBundle\Form\Type\VideoType;

class VideoConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, Video::class);
        $options['parent'] = $this->getOption('parent', $options, Video::class);
        $options['form'] = $this->getOption('form', $options, VideoType::class);
        $options['factory'] = $this->getOption('factory', $options, VideoFactory::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:Video');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:video.html.twig');
        $options['label'] = $this->getOption('label', $options, 'video.label.video');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'video';
    }
}