<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class TranslationStringMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'exchange');
        $this->setOption('label', $options, 'translation.label.translationString');
        $this->setOption('translationDomain', $options, 'EnhavoTranslationBundle');
        $this->setOption('route', $options, 'enhavo_translation_translation_string_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_TRANSLATION_STRINGS_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'translation';
    }
}