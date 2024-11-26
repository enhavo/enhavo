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

class ArchiveMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'archive',
            'label' => 'archive.label.archive',
            'translation_domain' => 'EnhavoRevisionBundle',
            'route' => 'enhavo_revision_admin_archive_index',
            'permission' => 'ROLE_ENHAVO_REVISION_ARCHIVE_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision_archive';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
