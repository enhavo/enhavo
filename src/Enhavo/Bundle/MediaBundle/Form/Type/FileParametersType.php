<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.09.17
 * Time: 23:57
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('alt', TextType::class, [
            'required' => false
        ]);
        $builder->add('title', TextType::class, [
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_file_parameters';
    }
}
