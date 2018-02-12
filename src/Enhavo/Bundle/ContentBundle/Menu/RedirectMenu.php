<?php
/**
 * RedirectMenuBuilder.php
 *
 * @since 01/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class RedirectMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'arrow-long-right');
        $this->setDefaultOption('label', $options, 'redirect.label.redirect');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoContentBundle');
        $this->setDefaultOption('route', $options, 'enhavo_content_redirect_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_CONTENT_REDIRECT_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'redirect';
    }
}