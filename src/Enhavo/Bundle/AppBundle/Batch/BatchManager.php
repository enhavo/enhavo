<?php
/**
 * BatchManager.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManager
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * ActionManager constructor.
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $checker)
    {
        $this->factory = $factory;
        $this->checker = $checker;
    }

    public function createBatchesViewData(array $configuration)
    {
        $data = [];
        $batches = $this->getBatches($configuration);
        foreach($batches as $key => $action) {
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
            $batch = $this->createBatch($options);

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

    public function getBatch($key, array $configuration)
    {
        $batches = $this->getBatches($configuration);
        if(isset($batches[$key])) {
            return $batches[$key];
        }
        return null;
    }

    /**
     * @param $options
     * @return Batch
     */
    public function createBatch($options): Batch
    {
        return $this->factory->create($options);
    }

    /**
     * @param Batch $batch
     * @param $resources
     * @return Response|null
     */
    public function executeBatch(Batch $batch, $resources): ?Response
    {
        return $batch->execute($resources);
    }
}
