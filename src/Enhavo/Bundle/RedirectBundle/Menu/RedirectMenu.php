<?php
/**
 * RedirectMenuBuilder.php
 *
 * @since 01/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\RedirectBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'compare_arrows',
            'label' => 'redirect.label.redirect',
            'translation_domain' => 'EnhavoRedirectBundle',
            'route' => 'enhavo_redirect_redirect_index',
            'role' => 'ROLE_ENHAVO_REDIRECT_REDIRECT_INDEX',
        ]);
    }

    public function getType()
    {
        return 'redirect';
    }
}