<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.01.18
 * Time: 16:34
 */

namespace Enhavo\Bundle\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeadLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);

        $choices = [];
        foreach($options['tags'] as $option) {
            $choices[$option] = $option;
        }

        $builder->add('tag', ChoiceType::class, [
            'choices' => $choices,
            'placeholder' => '---',
            'empty_data' => null,
        ]);

        $builder->addModelTransformer(new CallbackTransformer(
            function ($original) {
                if($original === null) {
                    return [
                        'text' => '',
                        'tag' => null
                    ];
                }

                if(preg_match("#<(.*?)>(.*?)</(.*?)>#i", $original, $match)) {
                    return [
                        'text' => $match[2],
                        'tag' => $match[1]
                    ];
                }

                return [
                    'text' => $original,
                    'tag' => null
                ];
            },
            function ($submitted) {
                if($submitted['tag'] !== null) {
                    return sprintf('<%s>%s</%s>', $submitted['tag'],  $submitted['text'],  $submitted['tag']);
                }
                return $submitted['text'];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'compound' => true,
            'tags' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
        ]);
    }

    public function getBlockPrefix()
    {
        return 'head_line';
    }
}