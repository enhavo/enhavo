<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoGenerator
{
    use ContainerAwareTrait;

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

    public function generate(RouteInterface $route)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($route->getContent());
        foreach($metadata->getGenerators() as $generatorConfig) {
            /** @var GeneratorInterface $generator */
            $generator = $this->collector->getType($generatorConfig->getType());
            $optionsResolver = new OptionsResolver();
            $generator->configureOptions($optionsResolver);
            $options = $optionsResolver->resolve($generatorConfig->getOptions());
            $generator->generate($route, $options);
        }
    }
}