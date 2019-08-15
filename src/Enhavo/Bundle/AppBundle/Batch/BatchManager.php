<?php
/**
 * BatchManager.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * ActionManager constructor.
     * @param TypeCollector $collector
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(TypeCollector $collector, AuthorizationCheckerInterface $checker)
    {
        $this->collector = $collector;
        $this->checker = $checker;
    }

    public function createBatchesViewData(array $configuration)
    {
        $data = [];
        $actions = $this->getBatches($configuration);
        foreach($actions as $key => $action) {
            $viewData = $action->createViewData();
            $viewData['key'] = $key;
            $data[] = $viewData;
        }
        return $data;
    }

    /**
     * @param array $configuration
     * @return Batch[]
     */
    public function getBatches(array $configuration)
    {
        $batches = [];
        foreach($configuration as $name => $options) {
            $batch = $this->createBatchFromOptions($options);

            if($batch->isHidden()) {
                continue;
            }

            if($batch->getPermission() !== null && !$this->checker->isGranted($batch->getPermission())) {
                continue;
            }

            $batches[$name] = $batch;
        }

        return $batches;
    }

    public function getBatch($type, array $configuration)
    {
        $batches = $this->getBatches($configuration);
        if(isset($batches[$type])) {
            return $batches[$type];
        }
        return null;
    }

    /**
     * @param $options
     * @return Batch
     * @throws TypeMissingException
     */
    private function createBatchFromOptions($options)
    {
        if(!isset($options['type'])) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Batch::class));
        }
        $type = $options['type'];
        unset($options['type']);
        return $this->createBatch($type, $options);
    }

    /**
     * @param $type
     * @param $options
     * @return Batch
     */
    public function createBatch($type, $options)
    {
        /** @var BatchTypeInterface $type */
        $type = $this->collector->getType($type);
        $batch = new Batch($type, $options);
        return $batch;
    }

    public function executeBatch(Batch $batch, $resources)
    {
        $batch->execute($resources);
    }
}
