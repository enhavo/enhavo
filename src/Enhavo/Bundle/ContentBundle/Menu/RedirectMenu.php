<?php
/**
 * RedirectMenuBuilder.php
 *
 * @since 01/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'arrow-long-right',
            'label' => 'redirect.label.redirect',
            'translationDomain' => 'EnhavoContentBundle',
            'route' => 'enhavo_content_redirect_index',
            'role' => 'ROLE_ENHAVO_CONTENT_REDIRECT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'redirect';
    }
}