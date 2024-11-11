<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 20:00
 */

namespace Enhavo\Bundle\RedirectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectType extends AbstractType
{
    public function __construct(
        private readonly string $model,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('from', TextType::class, [
            'label' => 'form.label.from',
            'translation_domain' => 'EnhavoRedirectBundle'
        ]);

        $builder->add('to', TextType::class, [
            'label' => 'form.label.to',
            'translation_domain' => 'EnhavoRedirectBundle'
        ]);

        $builder->add('code', ChoiceType::class, [
            'label' => 'form.label.code',
            'choices' => [
                '301' => '301',
                '302' => '302'
            ],
            'translation_domain' => 'EnhavoRedirectBundle'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->model
        ]);
    }
}
