<?php

namespace Enhavo\Bundle\AppBundle\Widget\Type;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TemplateWidget.php
 *
 * @since 05/05/19
 * @author gseidel
 */
class TemplateType extends AbstractWidgetType
{
    public function createViewData(array $options, $resource = null)
    {
        return $options['parameters'];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'parameters' => [],
        ]);
        $optionsResolver->setRequired('template');
    }

    public function getType()
    {
        return 'template';
    }
}
