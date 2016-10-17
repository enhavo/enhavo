<?php
/**
 * TextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\Text;
use Enhavo\Bundle\GridBundle\Form\Type\TextType;

class TextConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, Text::class);
        $options['parent'] = $this->getOption('parent', $options, Text::class);
        $options['form'] = $this->getOption('form', $options, TextType::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:Text');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:text.html.twig');
        $options['label'] = $this->getOption('label', $options, 'text.label.text');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'text';
    }
}