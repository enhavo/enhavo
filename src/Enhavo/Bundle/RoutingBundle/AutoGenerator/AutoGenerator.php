<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
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
        foreach($metadata->getGenerators() as $generatorConfig) {
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
