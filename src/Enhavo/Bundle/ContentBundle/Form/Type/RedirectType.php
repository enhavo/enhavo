<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 20:00
 */

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RedirectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('from', TextType::class, [
            'label' => 'form.label.from',
            'translation_domain' => 'EnhavoContentBundle'
        ]);

        $builder->add('to', TextType::class, [
            'label' => 'form.label.to',
            'translation_domain' => 'EnhavoContentBundle'
        ]);

        $builder->add('code', ChoiceType::class, [
            'label' => 'form.label.code',
            'choices' => [
                '301' => '301',
                '302' => '302'
            ],
            'translation_domain' => 'EnhavoContentBundle'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_content_redirect';
    }
}