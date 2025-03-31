<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class WysiwygVueTypeExtension extends AbstractVueTypeExtension
{
    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['configName'] = $view->vars['configName'];
    }

    public static function getExtendedTypes(): iterable
    {
        return [WysiwygType::class];
    }
}
