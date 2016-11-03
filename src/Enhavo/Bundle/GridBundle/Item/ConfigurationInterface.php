<?php
/**
 * ConfigurationInterface.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item;


use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ConfigurationInterface extends TypeInterface
{
    public function configure($name, $options);
}