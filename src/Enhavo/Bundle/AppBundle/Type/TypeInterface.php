<?php
/**
 * TypeInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;

interface TypeInterface
{
    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType();
}