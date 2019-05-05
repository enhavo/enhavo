<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-05
 * Time: 15:35
 */

namespace Enhavo\Bundle\AppBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractWidgetType implements WidgetTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {

    }

    public function getTemplate($options)
    {
        return $options['template'];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'template' => null
        ]);
    }
}
