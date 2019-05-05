<?php
/**
 * TaxonomyMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TaxonomyBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'apps',
            'translation_domain' => 'EnhavoTaxonomyBundle',
            'route' => 'enhavo_taxonomy_taxonomy_index',
            'role' => 'ROLE_ENHAVO_TAXONOMY_TAXONOMY_INDEX',
        ]);

        $resolver->setRequired(['label', 'taxonomy']);
    }

    public function getType()
    {
        return 'taxonomy';
    }
}
