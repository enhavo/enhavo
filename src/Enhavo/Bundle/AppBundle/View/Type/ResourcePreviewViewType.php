<?php
/**
 * ResourcePreviewViewer.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Controller\AppEventDispatcher;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\AppBundle\Preview\StrategyResolver;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourcePreviewViewType extends AbstractViewType
{
    public function __construct(
        private ViewUtil $util,
        private StrategyResolver $strategyResolver,
    ) { }

    public static function getName(): ?string
    {
        return 'resource_preview';
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        /** @var SingleResourceProvider $singleResourceProvider */
        $singleResourceProvider = $options['single_resource_provider'];

        /** @var NewResourceFactory $newResourceFactory */
        $newResourceFactory = $options['new_resource_factory'];

        /** @var ResourceFormFactory $resourceFormFactory */
        $resourceFormFactory = $options['resource_form_factory'];

        /** @var RepositoryInterface $repository */
        $repository = $options['repository'];

        /** @var FactoryInterface $repository */
        $factory = $options['factory'];

        /** @var AppEventDispatcher $appEventDispatcher */
        $appEventDispatcher = $options['app_event_dispatcher'];

        $configuration = $this->util->getRequestConfiguration($options);

        $appEventDispatcher->dispatchInitEvent(ResourceEvents::INIT_PREVIEW, $configuration);

        if ($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            $resource = $singleResourceProvider->get($configuration, $repository);
            $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);
        } else {
            $resource = $newResourceFactory->create($configuration, $factory);
            $this->util->isGrantedOr403($configuration, ResourceActions::CREATE);
        }

        $form = $resourceFormFactory->create($configuration, $resource);
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
        $response = $strategy->getPreviewResponse($resource, $strategyOptions);

        return $response;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'strategy' => 'service',
            'strategy_options' => null
        ]);

        $optionsResolver->setRequired([
            'metadata',
            'request_configuration',
            'resource_form_factory',
            'single_resource_provider',
            'new_resource_factory',
            'factory',
            'repository',
            'app_event_dispatcher',
        ]);
    }
}
