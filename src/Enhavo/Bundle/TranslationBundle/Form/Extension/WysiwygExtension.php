<?php
/**
 * WysiwygExtension.php
 *
 * @since 07/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\AppBundle\Form\Type\WysiwygType;

class WysiwygExtension extends TranslationExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return WysiwygType::class;
    }
}