<?php
/**
 * BlockInterface.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface BlockInterface extends TypeInterface
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