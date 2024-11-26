<?php
/**
 * RedirectMenuBuilder.php
 *
 * @since 01/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\RedirectBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'compare_arrows',
            'label' => 'redirect.label.redirect',
            'translation_domain' => 'EnhavoRedirectBundle',
            'route' => 'enhavo_redirect_admin_redirect_index',
            'permission' => 'ROLE_ENHAVO_REDIRECT_REDIRECT_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'redirect';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
