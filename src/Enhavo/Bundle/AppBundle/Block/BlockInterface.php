<?php
/**
 * BlockInterface.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Block;

interface BlockInterface
{
    /**
     * Feed a block with parameter and you it will
     * return a string. Normally html.
     *
     * @param $parameters array
     * @return string
     */
    public function render($parameters);
}