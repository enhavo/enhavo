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
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);

        if (is_array($options['tag_choices'])) {
            $choices = [];
            foreach($options['tag_choices'] as $option) {
                $choices[$option] = $option;
            }

            $builder->add('tag', ChoiceType::class, [
                'choices' => $choices,
                'placeholder' => $options['tag_placeholder'],
                'empty_data' => null,
            ]);
        }

        if (is_array($options['class_choices'])) {
            $choices = [];
            foreach($options['class_choices'] as $option) {
                $choices[$option] = $option;
            }

            $builder->add('class', ChoiceType::class, [
                'choices' => $choices,
                'placeholder' => 'class_placeholder',
                'empty_data' => null,
            ]);
        }

        $builder->addModelTransformer(new CallbackTransformer(
            function ($original) use ($options) {
                if($original === null) {
                    return [
                        'text' => '',
                        'tag' => null
                    ];
                } else if (preg_match("#<(.*?) class=['\"](.*?)['\"]>(.*?)</(.*?)>#i", $original, $match)) {
                    return [
                        'text' => html_entity_decode($match[3]),
                        'tag' => $match[1],
                        'class' => $match[2]
                    ];
                } else if(preg_match("#<(.*?)>(.*?)</(.*?)>#i", $original, $match)) {
                    return [
                        'text' => html_entity_decode($match[2]),
                        'tag' => $match[1],
                        'class' => ''
                    ];
                }

                return [
                    'text' => html_entity_decode(strip_tags($original)),
                    'tag' => null,
                    'class' => null
                ];
            },
            function ($submitted) use ($options) {
                $tag = $submitted['tag'] ?? $options['tag'];
                $class = $submitted['class'] ?? $options['class'];
                $text = htmlentities($submitted['text']);

                if($tag !== null && $class !== null) {
                    return sprintf('<%s class="%s">%s</%s>', $tag, $class, $text,  $tag);
                } else if($tag !== null) {
                    return sprintf('<%s>%s</%s>', $tag, $text, $tag);
                }

                return $text;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'tag' => null,
            'tag_choices' => null,
            'tag_placeholder' => '---',
            'class' => null,
            'class_choices' => null,
            'class_placeholder' => '---',
        ]);

        $resolver->addNormalizer('tag', function (Options $options, $value) {
            if ($value === null && ($options['class'] !== null || $options['class_choices'] !== null)) {
                throw new InvalidConfigurationException('If option "class" or "class_choices" is configured, the "tag" options must be set as well');
            }

            return $value;
        });
    }

    public function getBlockPrefix()
    {
        return 'html_tag';
    }
}
