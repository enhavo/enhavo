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

use Enhavo\Bundle\ResourceBundle\Tab\ArrangementParser;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adds the option "arrangement" to every form type: its children in rows and columns, in the same syntax as the
 * arrangement of a form tab.
 */
class ArrangementVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private readonly ArrangementParser $arrangementParser,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('arrangement', null);
        $resolver->setAllowedTypes('arrangement', ['null', 'string', 'array']);
    }

    public function finishVueData(FormView $view, VueData $data, array $options): void
    {
        if (null !== $options['arrangement']) {
            $data['arrangement'] = $this->arrangementParser->parse($options['arrangement']);
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}