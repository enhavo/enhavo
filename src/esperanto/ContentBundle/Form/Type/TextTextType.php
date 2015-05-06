<?php

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use esperanto\ContentBundle\Item\Type\TextText;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class TextTextType extends AbstractType
{
    protected $formName;
    protected $configuration;

    public function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'hidden', array(
            'data' => 'texttext'
        ));
        $builder->add('text1', 'wysiwyg');
        $builder->add('text2', 'wysiwyg');
        $builder->add('title1', 'text');
        $builder->add('title2', 'text');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($this->formName) {
            $view->vars['full_name'] = $this->formName.'[configuration]';
        } else {
            $view->vars['full_name'] = preg_replace('/\[form\]/', '', $view->vars['full_name']);
        }

        return;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Item\Type\TextText'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_texttext';
    }
} 