<?php
/**
 * TextExtension.php
 *
 * @since 07/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextExtension extends TranslationExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return TextType::class;
    }
}