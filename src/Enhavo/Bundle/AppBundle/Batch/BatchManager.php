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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if($type == null) {
            throw new NotFoundHttpException;
        }
        $options  = $requestConfiguration->getBatchOptions($type);
        /** @var BatchInterface $batch */
        $batch = $this->collector->getType($type);
        $batch = clone $batch;
        $batch->setOptions($options);
        if($batch->isGranted()) {
            $batch->execute($resources);
        } else {
            throw new AccessDeniedHttpException;
        }
    }
}