<?php
/**
 * TypeInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeExtensionInterface
{
    public static function getExtendedTypes(): array;

    public function configureOptions(OptionsResolver $resolver);
}
