<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Component\Type\TypeInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface BatchTypeInterface extends TypeInterface
{
    /**
     * @param $options array
     * @param ResourceInterface[] $resources
     * @param $resource
     * @return void
     * @throws BatchExecutionException
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null);

    /**
     * @param $options array
     * @param ViewData $data
     * @param ResourceInterface $resource
     * @return array
     */
    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null);

    /**
     * @param array $options
     * @return boolean
     */
    public function getPermission(array $options);

    /**
     * @param array $options
     * @return boolean
     */
    public function isHidden(array $options);
}
