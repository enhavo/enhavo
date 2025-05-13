<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\DateType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateVueTypeExtension extends AbstractVueTypeExtension
{
    public function buildVueData(FormView $view, VueData $data, array $options): void
    {
        $data['config'] = $options['config'];
        $data['allowTyping'] = $options['allow_typing'];
        $data['allowClear'] = $options['allow_clear'];
        $data['timepicker'] = false;
        $data['locale'] = $options['locale'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'form-date-time',
            'component_model' => 'DateTimeForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateType::class];
    }
}
