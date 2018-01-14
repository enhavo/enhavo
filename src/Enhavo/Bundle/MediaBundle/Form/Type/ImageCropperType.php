<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 14:45
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageCropperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $file = null;
        if($form->getParent()) {
            $file = $form->getParent()->getData();
        }

        $view->vars['formats'] = $options['formats'];
        $view->vars['file'] = $file;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'formats' => [],
            'label' => 'Format'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_image_cropper';
    }
}