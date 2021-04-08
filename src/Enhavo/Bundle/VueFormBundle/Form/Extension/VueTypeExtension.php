<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueTypeExtension extends AbstractTypeExtension
{
    /** @var array */
    private $types;

    public function addType()
    {

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $type = $form->getConfig()->getType()->getInnerType();
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => null
        ]);
    }

    public function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
