<?php
/**
 * SliderType.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use esperanto\SliderBundle\Form\Type\SlideType as BaseSlideType;

class SlideType extends BaseSlideType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('page', 'entity', array(
            'label' => ' ',
            'class' => 'esperantoProjectBundle:Page',
            'attr' => array('class' => 'link-type-page'),
        ));

        $builder->add('news', 'entity', array(
            'label' => ' ',
            'class' => 'esperantoProjectBundle:News',
            'property' => 'title',
            'attr' => array('class' => 'link-type-news'),
        ));

        $builder->add('reference', 'entity', array(
            'label' => ' ',
            'class' => 'esperantoProjectBundle:Reference',
            'property' => 'title',
            'attr' => array('class' => 'link-type-reference'),
        ));

        $builder->add('link_type', 'choice', array(
            'choices'   => array(
                'label'   => 'form.label.link_type.no_link',
                'external'   => 'Externer Link',
                'news' => 'News',
                'page'   => 'Seite',
                'reference'   => 'Referenz',
            ),
            'multiple'  => false,
            'expanded' => true,
            'label' => 'form.label.link_type',
            'attr' => array('class' => 'link-type-selector'),
        ));
    }
} 