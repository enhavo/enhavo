<?php
/**
 * PicturePictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\PicturePicture;
use Enhavo\Bundle\GridBundle\Form\Type\PicturePictureType;

class PicturePictureConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, PicturePicture::class);
        $options['parent'] = $this->getOption('parent', $options, PicturePicture::class);
        $options['form'] = $this->getOption('form', $options, PicturePictureType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:TextPicture');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:text_text.html.twig');
        $options['label'] = $this->getOption('label', $options, 'textText.label.textText');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'picture_picture';
    }
}