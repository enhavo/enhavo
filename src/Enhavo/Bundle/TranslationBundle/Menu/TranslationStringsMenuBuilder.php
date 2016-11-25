<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class TranslationStringsMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'exchange');
        $this->setOption('label', $options, 'translation.label.translationStrings');
        $this->setOption('translationDomain', $options, 'EnhavoTranslationBundle');
        $this->setOption('route', $options, 'enhavo_translation_strings_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_TRANSLATION_STRINGS_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'translation';
    }
}