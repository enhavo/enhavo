<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Widget\Type;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
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
