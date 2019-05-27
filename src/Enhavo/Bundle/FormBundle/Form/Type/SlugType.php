<?php
/**
 * SlugType.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlugType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle'
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_slug';
    }

    public function getParent()
    {
        return TextType::class;
    }
}
