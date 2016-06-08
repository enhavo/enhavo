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
        $translator = $this->container->get('translator');
        $translationDomain = isset($options['translationDomain']) ? $options['translationDomain'] : null;
        return $translator->trans($options['label'], [], $translationDomain);
    }

    /**
     * @inheritdoc
     */
    public function getWidth($options)
    {
        return isset($options['width']) ? $options['width'] : 1;
    }
}
