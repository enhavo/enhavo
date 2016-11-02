<?php
/**
 * TextTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\TextText;
use Enhavo\Bundle\GridBundle\Factory\TextTextFactory;
use Enhavo\Bundle\GridBundle\Form\Type\TextTextType;

class TextTextConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, TextText::class);
        $options['parent'] = $this->getOption('parent', $options, TextText::class);
        $options['form'] = $this->getOption('form', $options, TextTextType::class);
        $options['factory'] = $this->getOption('factory', $options, TextTextFactory::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:TextText');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:text_text.html.twig');
        $options['label'] = $this->getOption('label', $options, 'textText.label.textText');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'text_text';
    }
}