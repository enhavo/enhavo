<?php
/**
 * BatchManager.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class BatchManager
{
    protected $collector;

    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    public function executeBatch($resources, RequestConfigurationInterface $requestConfiguration)
    {
        $type = $requestConfiguration->getBatchType();
        /** @var BatchInterface $batch */
        $batch = $this->collector->getType($type);
        $batch = clone $batch;
        $batch->execute($resources);
    }
}