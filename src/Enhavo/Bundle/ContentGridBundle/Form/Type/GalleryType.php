<?php
/**
 * GalleryType.php
 */

namespace enhavo\ContentBundle\Form\Type;

use enhavo\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GalleryType extends ItemFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('text', 'wysiwyg', array(
            'label' => 'form.label.text'
        ));

        $builder->add('files', 'enhavo_files', array(
            'label' => 'form.label.pictures'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'enhavo\ContentBundle\Entity\Gallery'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_item_gallery';
    }
} 