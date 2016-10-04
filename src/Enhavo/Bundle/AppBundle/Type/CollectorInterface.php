<?php
/**
 * CollectorInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;



interface CollectorInterface
{
    public function add($alias, $id);

    public function getType($name);
}