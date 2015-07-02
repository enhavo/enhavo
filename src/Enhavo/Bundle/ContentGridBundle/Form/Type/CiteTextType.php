<?php
/**
 * CiteTextType.php
 *
 */

namespace enhavo\ContentBundle\Form\Type;

use enhavo\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use enhavo\ContentBundle\Item\Type\Text;

class CiteTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cite', 'textarea', array(
            'label' => 'form.label.cite'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'enhavo\ContentBundle\Entity\CiteText'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_item_citetext';
    }
} 