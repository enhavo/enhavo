<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManager
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly AuthorizationCheckerInterface $checker,
    ) {
    }

    /**
     * @return Batch[]
     */
    public function getBatches(array $configuration, EntityRepository $repository): array
    {
        $batches = [];
        foreach ($configuration as $name => $options) {
            $batch = $this->factory->create($options, $name);

            if (!$batch->isEnabled()) {
                continue;
            }

            if (null !== $batch->getPermission($repository) && !$this->checker->isGranted($batch->getPermission($repository))) {
                continue;
            }

            $batches[$name] = $batch;
        }

        return $batches;
    }

    public function getBatch($key, array $configuration, EntityRepository $repository): ?Batch
    {
        $batches = $this->getBatches($configuration, $repository);
        if (isset($batches[$key])) {
            return $batches[$key];
        }

        return null;
    }
}
