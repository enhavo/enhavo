<?php

namespace App\Form\Type\Block;

use App\Entity\Block\TestBlock;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('direction', ChoiceType::class, [
            'choices' => [
                'Left' => 'left',
                'Right' => 'right',
            ],
        ]);

        $builder->add('directions', ChoiceType::class, [
            'choices' => [
                'Left' => 'left',
                'Right' => 'right',
            ],
            'multiple' => true,
        ]);

        $builder->add('leftText', TextType::class, [
            'component_visible_condition' => 'form.parent.get("directions").getModelValue().indexOf("left") >= 0',
        ]);
        $builder->add('rightText', WysiwygType::class, [
            'component_visible_condition' => 'form.parent.get("directions").getModelValue().indexOf("right") >= 0',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TestBlock::class,
        ]);
    }
}
