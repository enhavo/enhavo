<?php
/**
 * BaseConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\GridBundle\Item\ConfigurationInterface;
use Enhavo\Bundle\GridBundle\Item\ItemConfiguration;

class BaseConfiguration extends AbstractType implements ConfigurationInterface
{
    public function configure($name, $options)
    {
        $itemConfiguration = new ItemConfiguration();

        $itemConfiguration->setName($name);
        $itemConfiguration->setModel($this->getRequiredOption('model', $options));
        $itemConfiguration->setLabel($this->getRequiredOption('label', $options));
        $itemConfiguration->setForm($this->getRequiredOption('form', $options));
        $itemConfiguration->setRepository($this->getRequiredOption('repository', $options));
        $itemConfiguration->setTemplate($this->getRequiredOption('template', $options));

        $itemConfiguration->setParent($this->getOption('parent', $options));
        $itemConfiguration->setTranslationDomain($this->getOption('translationDomain', $options));
        $itemConfiguration->setOptions($this->getOption('options', $options, []));

        return $itemConfiguration;
    }

    public function getType()
    {
        return 'base';
    }
}