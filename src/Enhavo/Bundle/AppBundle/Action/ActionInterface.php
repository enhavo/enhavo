<?php
/**
 * ActionInterface.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ActionInterface extends TypeInterface
{
    /**
     * Feed a action with parameter and you it will
     * return a string. Normally html.
     *
     * @param $parameters array
     * @return string
     */
    public function render($parameters);
}