<?php
/**
 * GalleryConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\Gallery;
use Enhavo\Bundle\GridBundle\Form\Type\GalleryType;

class GalleryConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, Gallery::class);
        $options['parent'] = $this->getOption('parent', $options, Gallery::class);
        $options['form'] = $this->getOption('form', $options, GalleryType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:Gallery');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:gallery.html.twig');
        $options['label'] = $this->getOption('label', $options, 'gallery.label.gallery');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'gallery';
    }
}