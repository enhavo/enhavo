<?php
/**
 * TextType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PicturePictureType extends ItemFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');
        $builder->add('filesLeft', 'esperanto_files');
        $builder->add('filesRight', 'esperanto_files');
        $builder->add('frame', 'choice', array(
            'label' => 'form.label.public',
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
            'data_class' => 'esperanto\ContentBundle\Entity\PicturePicture'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_picture_picture';
    }
} 