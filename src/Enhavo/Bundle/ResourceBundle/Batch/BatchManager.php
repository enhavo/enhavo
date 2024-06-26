<?php
/**
 * BatchManager.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
    )
    {
    }

    /**
     * @return Batch[]
     */
    public function getBatches(array $configuration, EntityRepositoryInterface $repository): array
    {
        $batches = [];
        foreach($configuration as $name => $options) {
            $batch = $this->factory->create($options);

            if (!$batch->isEnabled()) {
                continue;
            }

            if ($batch->getPermission($repository) !== null && !$this->checker->isGranted($batch->getPermission($repository))) {
                continue;
            }

            $batches[$name] = $batch;
        }

        return $batches;
    }

    public function getBatch($key, array $configuration, EntityRepositoryInterface $repository): ?Batch
    {
        $batches = $this->getBatches($configuration, $repository);
        if (isset($batches[$key])) {
            return $batches[$key];
        }
        return null;
    }
}
