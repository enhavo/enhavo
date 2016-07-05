<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface BatchInterface extends TypeInterface
{
    public function execute($resources);

    public function setOptions($parameters);

    public function getConfirmMessage();

    public function getLabel();
}