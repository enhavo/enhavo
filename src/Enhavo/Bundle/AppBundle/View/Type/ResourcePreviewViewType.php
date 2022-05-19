<?php
/**
 * ResourcePreviewViewer.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Preview\StrategyResolver;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourcePreviewViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ViewUtil $util,
        private StrategyResolver $strategyResolver,
        private SingleResourceProviderInterface $singleResourceProvider,
        private NewResourceFactoryInterface $newResourceFactory,
        private ResourceFormFactoryInterface $resourceFormFactory,
        private ResourceManager $resourceManager,
    ) {}

    public static function getName(): ?string
    {
        return 'resource_preview';
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->util->getRequestConfiguration($options);

        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
        $factory = $this->resourceManager->getFactory($metadata->getApplicationName(), $metadata->getName());

        if ($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            $resource = $this->singleResourceProvider->get($configuration, $repository);
            $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);
        } else {
            $resource = $this->newResourceFactory->create($configuration, $factory);
            $this->util->isGrantedOr403($configuration, ResourceActions::CREATE);
        }

        $form = $this->resourceFormFactory->create($configuration, $resource);
        $form->handleRequest($request);

        $strategyName = $this->util->mergeConfig([
            $options['strategy'],
            $this->util->getViewerOption('strategy', $options['request_configuration'])
        ]);

        $strategyOptions = $this->util->mergeConfigArray([
            ['service' => 'enhavo_app.preview.default_renderer:render'],
            $options['strategy_options'],
            $this->util->getViewerOption('strategy_options', $options['request_configuration'])
        ]);

        $strategy = $this->strategyResolver->getStrategy($strategyName);
        return $strategy->getPreviewResponse($resource, $strategyOptions);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'strategy' => 'service',
            'strategy_options' => null
        ]);

        $optionsResolver->setRequired('request_configuration');
    }
}
