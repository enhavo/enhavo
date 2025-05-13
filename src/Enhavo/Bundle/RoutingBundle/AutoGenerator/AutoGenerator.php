<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;

class AutoGenerator
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    public function __construct(CollectorInterface $collector, MetadataRepository $metadataRepository)
    {
        $this->collector = $collector;
        $this->metadataRepository = $metadataRepository;
    }

    public function generate($resource)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        if (null === $metadata) {
            return;
        }

        foreach ($metadata->getGenerators() as $generatorConfig) {
            /** @var GeneratorInterface $generator */
            $type = $this->collector->getType($generatorConfig->getType());
            $generator = $this->createGenerator($type, $generatorConfig->getOptions(), $resource);
            $generator->generate();
        }
    }

    private function createGenerator($type, $options, $resource)
    {
        return new Generator($type, $options, $resource);
    }
}
