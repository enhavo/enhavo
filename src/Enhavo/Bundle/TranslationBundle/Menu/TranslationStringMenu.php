<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class TranslationStringMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'exchange');
        $this->setDefaultOption('label', $options, 'translation.label.translationString');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoTranslationBundle');
        $this->setDefaultOption('route', $options, 'enhavo_translation_translation_string_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_TRANSLATION_TRANSLATION_STRING_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'translation';
    }
}