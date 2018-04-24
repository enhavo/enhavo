<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.03.18
 * Time: 15:27
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ItemTypeInterface extends TypeInterface
{
    /**
     * @return string
     */
    public function getModel($options);

    /**
     * @return string
     */
    public function getForm($options);

    /**
     * @return string
     */
    public function getRepository($options);

    /**
     * @return string
     */
    public function getLabel($options);

    /**
     * @return string
     */
    public function getFactory($options);
}