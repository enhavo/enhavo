<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationStringMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'exchange',
            'label' => 'translation.label.translationString',
            'translation_domain' => 'EnhavoTranslationBundle',
            'route' => 'enhavo_translation_translation_string_index',
            'role' => 'ROLE_ENHAVO_TRANSLATION_TRANSLATION_STRING_INDEX',
        ]);
    }

    public function getType()
    {
        return 'translation';
    }
}