<?php
/**
 * SlugTranslationStrategy.php
 *
 * @since 20/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;

class SlugTranslationType extends AbstractTranslationType
{
    public static function getName(): ?string
    {
        return 'slug';
    }

    public static function getParentType(): ?string
    {
        return TextTranslationType::class;
    }

}
