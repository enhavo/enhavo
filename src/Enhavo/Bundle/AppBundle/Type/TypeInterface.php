<?php

namespace Enhavo\Bundle\AppBundle\Type;

/**
 * @author gseidel
 */
interface TypeInterface
{
    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType();
}
