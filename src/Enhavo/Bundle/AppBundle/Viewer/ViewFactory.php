<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var TypeCollector
     */
    protected $collector;

    public function __construct(ContainerInterface $container, TypeCollector $collector)
    {
        $this->container = $container;
        $this->collector = $collector;
    }

    public function create($type, $options = []): View
    {
        /** @var ViewerInterface $viewer */
        $viewer = $this->collector->getType($type);
        $resolver = new OptionsResolver();
        $viewer->configureOptions($resolver);
        return $viewer->createView($resolver->resolve($options));
    }
}