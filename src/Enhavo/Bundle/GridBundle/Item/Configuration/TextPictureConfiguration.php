<?php
/**
 * TextPictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\TextPicture;
use Enhavo\Bundle\GridBundle\Form\Type\TextPictureType;

class TextPictureConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, TextPicture::class);
        $options['parent'] = $this->getOption('parent', $options, TextPicture::class);
        $options['form'] = $this->getOption('form', $options, TextPictureType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:TextPicture');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:text_picture.html.twig');
        $options['label'] = $this->getOption('label', $options, 'textPicture.label.textPicture');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'text_picture';
    }
}