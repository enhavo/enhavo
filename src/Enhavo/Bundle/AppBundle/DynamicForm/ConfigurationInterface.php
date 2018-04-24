<?php
/**
 * ConfigurationInterface.php
 *
 * @since 15/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ConfigurationInterface extends TypeInterface
{
    public function configure($name, $options);
}