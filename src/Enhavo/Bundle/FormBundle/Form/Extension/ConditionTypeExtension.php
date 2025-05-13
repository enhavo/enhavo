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

use Enhavo\Bundle\FormBundle\Form\Config\Condition;
use Enhavo\Bundle\FormBundle\Form\Config\ConditionObserver;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConditionTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'condition' => null,
            'condition_observer' => null,
            'condition_scope' => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // subject
        $condition = $options['condition'];
        if ($condition instanceof Condition) {
            $view->vars['attr']['data-condition-type'] = json_encode([
                'id' => $condition->getId(),
            ]);
        }

        // observer
        if (!is_array($options['condition_observer'])) {
            $conditionObservers = [$options['condition_observer']];
        } else {
            $conditionObservers = $options['condition_observer'];
        }
        $data = [];
        foreach ($conditionObservers as $conditionObserver) {
            if ($conditionObserver instanceof ConditionObserver) {
                $data[] = [
                    'id' => $conditionObserver->getSubject()->getId(),
                    'values' => $conditionObserver->getValues(),
                    'operator' => $conditionObserver->getOperator(),
                    'scope' => $conditionObserver->getScope(),
                ];
            }
        }
        if (count($data) > 0) {
            $view->vars['attr']['data-condition-type-observer'] = json_encode($data);
        }

        // scope
        if (isset($options['condition_scope'])) {
            $view->vars['attr']['data-condition-scope'] = $options['condition_scope'];
        }
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
