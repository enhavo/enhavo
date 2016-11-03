<?php
/**
 * CiteTextConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Entity\CiteText;
use Enhavo\Bundle\GridBundle\Factory\CiteTextFactory;
use Enhavo\Bundle\GridBundle\Form\Type\CiteTextType;

class CiteTextConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, CiteText::class);
        $options['parent'] = $this->getOption('parent', $options, CiteText::class);
        $options['form'] = $this->getOption('form', $options, CiteTextType::class);
        $options['factory'] = $this->getOption('factory', $options, CiteTextFactory::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoGridBundle:CiteText');
        $options['template'] = $this->getOption('template', $options, 'EnhavoGridBundle:Item:cite_text.html.twig');
        $options['label'] = $this->getOption('label', $options, 'citeText.label.citeText');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');
        
        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'cite_text';
    }
}