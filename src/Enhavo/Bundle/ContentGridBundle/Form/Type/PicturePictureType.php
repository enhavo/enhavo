<?php
/**
 * TextType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\ContentBundle\Form\Type;

use enhavo\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PicturePictureType extends ItemFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('filesLeft', 'enhavo_files', array(
            'label' => 'form.label.picture_left'
        ));

        $builder->add('captionLeft', 'text', array(
            'label' => 'form.label.caption_left'
        ));

        $builder->add('filesRight', 'enhavo_files', array(
            'label' => 'form.label.picture_right'
        ));

        $builder->add('captionRight', 'text', array(
            'label' => 'form.label.caption_right'
        ));

        $builder->add('frame', 'choice', array(
            'label' => 'form.label.frame',
            'choices'   => array(
                '1' => 'label.yes',
                '0' => 'label.no'
            ),
            'expanded' => true,
            'multiple' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'enhavo\ContentBundle\Entity\PicturePicture'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_item_picture_picture';
    }
} 