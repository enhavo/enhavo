<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-24
 * Time: 20:26
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CSRFMockExtension implements FormTypeExtensionInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => true]);
    }

    public function getExtendedType()
    {
        return FormType::class;
    }

    public function getExtendedTypes()
    {
        return [FormType::class];
    }
}
