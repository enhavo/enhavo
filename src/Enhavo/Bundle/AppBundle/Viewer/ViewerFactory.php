<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;

class ViewerFactory
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

    public function create(
        RequestConfigurationInterface $configuration,
        MetadataInterface $metadata = null,
        $newResource = null,
        Form $form = null,
        $defaultType = null)
    {
        $viewerType = $configuration->getViewerType() ? $configuration->getViewerType() : $defaultType;

        /** @var ViewerInterface $viewer */
        $viewer = $this->collector->getType($viewerType);
        $viewer = clone $viewer;

        $viewer->setContainer($this->container);
        $viewer->setConfiguration($configuration);

        if($metadata) {
            $viewer->setMetadata($metadata);
        }

        if($form) {
            $viewer->setForm($form);
        }

        if($newResource) {
            $viewer->setResource($newResource);
        }

        $optionAccessor = new OptionAccessor();
        $viewer->configureOptions($optionAccessor);
        $optionAccessor->resolve($configuration->getViewerOptions());
        $viewer->setOptionAccessor($optionAccessor);

        return $viewer;
    }

    public function createType(RequestConfigurationInterface $configuration, $type)
    {
        return $this->create($configuration, null, null, null, $type);
    }
}