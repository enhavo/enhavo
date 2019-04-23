<?php
/**
 * SlugType.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\SlugType as AppSlugType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlugType extends AppSlugType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));
    }
}