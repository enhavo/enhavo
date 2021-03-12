<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.01.18
 * Time: 16:34
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeadLineType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'tag_choices' => [
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6'
            ]
        ]);
    }

    public function getParent()
    {
        return HtmlTagType::class;
    }
}
