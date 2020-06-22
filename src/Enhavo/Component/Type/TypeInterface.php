<?php
/**
 * TypeInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    /**
     * Returns a unique name for this type
     *
     * @return string
     */
    public static function getName(): ?string;

    /**
     * Returns the parent type
     *
     * @return string
     */
    public static function getParentType(): ?string;

    /**
     * @param $parent TypeInterface
     */
    public function setParent(TypeInterface $parent);

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
}
