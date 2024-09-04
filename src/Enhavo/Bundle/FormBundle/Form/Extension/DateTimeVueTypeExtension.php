<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class DateTimeVueTypeExtension extends AbstractVueTypeExtension
{
    public function buildVueData(FormView $view, VueData $data, array $options): void
    {
        $data['timepicker'] = true;
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateTimeType::class];
    }
}
