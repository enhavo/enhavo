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
            'tag_choices' => ['h1' => 0, 'h2' => 1, 'h3' => 2, 'h4' => 3, 'h5' => 4, 'h6' => 5]
        ]);
    }

    public function getParent()
    {
        return HtmlTagType::class;
    }
}
