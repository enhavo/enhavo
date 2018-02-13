<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.01.18
 * Time: 14:54
 */

namespace Enhavo\Bundle\AppBundle\Form\Extension;

use Enhavo\Bundle\AppBundle\Form\Config\Condition;
use Enhavo\Bundle\AppBundle\Form\Config\ConditionObserver;
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
            'condition_observer' => null
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $condition = $options['condition'];
        if($condition instanceof Condition) {
            $view->vars['attr']['data-condition-type'] = json_encode([
                'id' => $condition->getId()
            ]);
        }

        $conditionObserver = $options['condition_observer'];
        if($conditionObserver instanceof ConditionObserver) {
            $view->vars['attr']['data-condition-type-observer'] = json_encode([
                'id' => $conditionObserver->getSubject()->getId(),
                'values' => $conditionObserver->getValues()
            ]);
        }
    }

    public function getExtendedType()
    {
        return FormType::class;
    }
}