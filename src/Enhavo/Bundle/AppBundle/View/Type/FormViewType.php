<?php
/**
 * @author blutze-media
 * @since 2022-04-09
 */

/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\View\AbstractViewType;

class FormViewType extends AbstractViewType
{

    public static function getName(): ?string
    {
        return 'form';
    }

    public static function getParentType(): ?string
    {
        return UpdateViewType::class;
    }

}
