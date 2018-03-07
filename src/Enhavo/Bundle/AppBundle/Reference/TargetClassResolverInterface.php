<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\AppBundle\Reference;


interface TargetClassResolverInterface
{
    /**
     * @param $value string
     * @return string
     */
    public function resolveClass($value);

    /**
     * @param integer $id
     * @param string $class
     * @return object
     */
    public function find($id, $class);
}