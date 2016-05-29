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
    public function add(TypeInterface $type);

    public function getCollection();

    public function getType($name);
}