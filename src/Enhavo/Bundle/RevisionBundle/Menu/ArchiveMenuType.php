<?php
/**
 * ArticleMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RevisionBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
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
            'role' => 'ROLE_ENHAVO_REVISION_ARCHIVE_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision_archive';
    }
}
