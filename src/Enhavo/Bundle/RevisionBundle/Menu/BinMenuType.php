<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RevisionBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'local_drink',
            'label' => 'bin.label.bin',
            'translation_domain' => 'EnhavoRevisionBundle',
            'route' => 'enhavo_revision_admin_bin_index',
            'permission' => 'ROLE_ENHAVO_REVISION_BIN_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision_bin';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
