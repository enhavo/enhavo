<?php
/**
 * RedirectMenuBuilder.php
 *
 * @since 01/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class RedirectMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'arrow-long-right');
        $this->setOption('label', $options, 'redirect.label.redirect');
        $this->setOption('translationDomain', $options, 'EnhavoContentBundle');
        $this->setOption('route', $options, 'enhavo_content_redirect_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_CONTENT_REDIRECT_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'redirect';
    }
}