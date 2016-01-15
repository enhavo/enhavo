<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\CallbackTransformer;

class BooleanType extends AbstractType
{
    const VALUE_TRUE = 'true';
    const VALUE_FALSE = 'false';
    const VALUE_NULL = 'null';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) {
                if(true === $originalDescription) {
                    return self::VALUE_TRUE;
                }
                if(false === $originalDescription) {
                    return self::VALUE_FALSE;
                }
                if(null === $originalDescription) {
                    return self::VALUE_NULL;
                }
                return $originalDescription;
            },
            function ($submittedDescription) {
                if(self::VALUE_TRUE === $submittedDescription) {
                    return true;
                }
                if(self::VALUE_FALSE === $submittedDescription) {
                    return false;
                }
                return null;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label_true'] = $options['label_true'];
        $view->vars['label_false'] = $options['label_false'];

        $value = $view->vars['value'];
        if($value === self::VALUE_NULL) {
            if(true === $options['default']) {
                $value = self::VALUE_TRUE;
            }
            if(false === $options['default']) {
                $value = self::VALUE_FALSE;
            }
            if(null === $options['default']) {
                $value = self::VALUE_NULL;
            }
            $view->vars['value'] = $value;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                self::VALUE_TRUE => 'true',
                self::VALUE_FALSE => 'false'
            ),
            'label_true' =>  'label.yes',
            'label_false' => 'label.no',
            'translation_domain' => 'EnhavoAppBundle',
            'expanded' => true,
            'multiple' => false,
            'default' => null
        ));

        $resolver->setNormalizer('choices', function (Options $options, $value) {
            return array(
                self::VALUE_TRUE => $options['label_true'],
                self::VALUE_FALSE => $options['label_false']
            );
        });
    }

    public function getName()
    {
        return 'enhavo_boolean';
    }

    public function getParent()
    {
        return 'choice';
    }
}