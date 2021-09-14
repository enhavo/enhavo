<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.01.18
 * Time: 14:54
 */

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'list' => false
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['list'] = $options['list'];
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChoiceType::class];
    }
}
