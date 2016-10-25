<?php

namespace Enhavo\Bundle\AppBundle\Table;

use Enhavo\Bundle\AppBundle\Type\AbstractType;

abstract class AbstractTableWidget extends AbstractType implements TableWidgetInterface
{
    /**
     * @inheritdoc
     */
    abstract function render($options, $resource);

    /**
     * @inheritdoc
     */
    public function getLabel($options)
    {
        $label = $this->getOption('label', $options, '');
        $translationDomain = $this->getOption('translationDomain', $options);
        $translator = $this->container->get('translator');
        return $translator->trans($label, [], $translationDomain);
    }

    /**
     * @inheritdoc
     */
    public function getWidth($options)
    {
        return isset($options['width']) ? $options['width'] : 1;
    }
}
