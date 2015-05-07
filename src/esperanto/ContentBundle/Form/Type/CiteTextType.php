<?php
/**
 * CiteTextType.php
 *
 */

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\ContentBundle\Item\Type\Text;

class CiteTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', 'wysiwyg');
        $builder->add('title', 'text');
        $builder->add('cite', 'wysiwyg');

        $builder->add('textleft', 'choice', array(
            'label' => 'form.label.textleft',
            'choices'   => array(
                '1' => 'label.text_left-picture_right',
                '0' => 'label.picture_left-text_right'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\CiteText'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_citetext';
    }
} 