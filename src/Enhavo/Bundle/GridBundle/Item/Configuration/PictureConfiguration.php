<?php
/**
 * PictureConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\Picture;
use Enhavo\Bundle\GridBundle\Form\Type\PictureType;

class PictureConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, Picture::class);
        $options['parent'] = $this->getOption('parent', $options, Picture::class);
        $options['form'] = $this->getOption('form', $options, PictureType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:Picture');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:picture.html.twig');
        $options['label'] = $this->getOption('label', $options, 'picture.label.picture');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'picture';
    }
}