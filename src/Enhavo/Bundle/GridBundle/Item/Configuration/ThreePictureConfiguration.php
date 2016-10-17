<?php
/**
 * ThreePictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\ThreePicture;
use Enhavo\Bundle\GridBundle\Form\Type\ThreePictureType;

class ThreePictureConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, ThreePicture::class);
        $options['parent'] = $this->getOption('parent', $options, ThreePicture::class);
        $options['form'] = $this->getOption('form', $options, ThreePictureType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:ThreePicture');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:three_picture.html.twig');
        $options['label'] = $this->getOption('label', $options, 'threePicture.label.threePicture');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'three_picture';
    }
}