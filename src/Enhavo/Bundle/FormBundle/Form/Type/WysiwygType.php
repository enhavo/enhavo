<?php
/**
 * WysiwygType.php
 *
 * @since 31/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class WysiwygType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'form-wysiwyg',
            'component_model' => 'WysiwygForm',
        ]);
    }

    public function getParent()
    {
        return TextareaType::class;
    }
}
