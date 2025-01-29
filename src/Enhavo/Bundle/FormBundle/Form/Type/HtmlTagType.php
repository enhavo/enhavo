<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.01.18
 * Time: 16:34
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);

        if (is_array($options['tag_choices'])) {
            $builder->add('tag', ChoiceType::class, [
                'choices' => $options['tag_choices'],
                'placeholder' => $options['tag_placeholder'],
                'empty_data' => $options['tag_empty_data'],
            ]);
        }

        if (is_array($options['class_choices'])) {
            $builder->add('class', ChoiceType::class, [
                'choices' => $options['class_choices'],
                'placeholder' => $options['class_placeholder'],
                'empty_data' => $options['class_empty_data'],
            ]);
        }

        $builder->addModelTransformer(new CallbackTransformer(
            function ($original) use ($options) {
                if($original === null) {
                    return [
                        'text' => '',
                        'tag' => null
                    ];
                } else if (preg_match("#^<([a-z0-9-_]*?) class=['\"](.*?)['\"]>(.*?)</([a-z0-9-_]*?)>$#i", $original, $match)) {
                    return [
                        'text' => html_entity_decode($match[3]),
                        'tag' => $match[1],
                        'class' => $match[2]
                    ];
                } else if(preg_match("#^<([a-z0-9-_]*?)>(.*?)</([a-z0-9-_]*?)>$#i", $original, $match)) {
                    return [
                        'text' => html_entity_decode($match[2]),
                        'tag' => $match[1],
                        'class' => ''
                    ];
                }

                return [
                    'text' => $original,
                    'tag' => null,
                    'class' => null
                ];
            },
            function ($submitted) use ($options) {
                $tag = $submitted['tag'] ?? $options['tag_empty_data'];
                $class = $submitted['class'] ?? $options['class_empty_data'];

                $text = $submitted['text'];

                if($tag !== null && $class !== null) {
                    return sprintf('<%s class="%s">%s</%s>', $tag, $class, $text,  $tag);
                } else if($tag !== null) {
                    return sprintf('<%s>%s</%s>', $tag, $text, $tag);
                } else if($class !== null) {
                    $tag = $options['class_fallback_tag'];
                    return sprintf('<%s class="%s">%s</%s>', $tag, $class, $text,  $tag);
                }

                return $text;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'tag_choices' => null,
            'tag_placeholder' => '---',
            'tag_empty_data' => null,
            'class_choices' => null,
            'class_placeholder' => '---',
            'class_empty_data' => null,
            'class_fallback_tag' => 'span',
            'error_bubbling' => false,
        ]);

        $resolver->setAllowedTypes('class_fallback_tag', 'string');
    }

    public function getBlockPrefix()
    {
        return 'html_tag';
    }
}
