<?php
/**
 * GeneratorInterface.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

use Enhavo\Bundle\AppBundle\Route\Routeable;

interface GeneratorInterface
{
    /**
     * @param Routeable $routeable
     * @return string
     */
    public function generate(Routeable $routeable);
}