<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:27
 */

namespace Enhavo\Bundle\RoutingBundle\Router;

use Doctrine\Common\Proxy\Proxy;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Router
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

    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $type = 'default')
    {
        $metadata = $this->metadataRepository->getMetadata($resource);

        if ($metadata === null) {
            throw new \InvalidArgumentException(sprintf('Can\'t find routing metadata to generate url for class "%s".', get_class($resource)));
        }

        /** @var Metadata $metadata */
        foreach($metadata->getRouter() as $router) {
            if($router->getName() == $type) {
                /** @var StrategyInterface $strategy */
                $strategy = $this->collector->getType($router->getType());
                $optionsResolver = new OptionsResolver();
                $strategy->configureOptions($optionsResolver);
                $options = $optionsResolver->resolve($router->getOptions());
                return $strategy->generate($resource , $parameters, $referenceType, $options);
            }
        }

        $className = get_class($resource);
        if($resource instanceof Proxy) {
            $className = get_parent_class($resource);
        }

        throw new UrlResolverException(
            sprintf('Can\'t resolve route for class "%s" and type "%s". Maybe you need to add to router configuration', $className, $type)
        );
    }
}
