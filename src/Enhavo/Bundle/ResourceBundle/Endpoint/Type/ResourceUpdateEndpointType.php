<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Grid\GridManager;
use Enhavo\Bundle\AppBundle\View\AbstractResourceFormType;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResourceUpdateEndpointType extends AbstractEndpointType
{
    public function __construct(
        array $formThemes,
        ActionManager $actionManager,
        RequestStack $requestStack,
        private ViewUtil $util,
        RouterInterface $router,
        TranslatorInterface $translator,
        private ResourceManager $resourceManager,
        GridManager $gridManager,
        ResourceFormFactoryInterface $resourceFormFactory,
        NormalizerInterface $normalizer,
        EventDispatcherInterface $eventDispatcher,
        private SingleResourceProviderInterface $singleResourceProvider,
    ) {
        parent::__construct($formThemes, $actionManager, $requestStack, $util, $router, $translator, $resourceManager, $gridManager, $resourceFormFactory, $normalizer, $eventDispatcher);
    }

    public static function getName(): ?string
    {
        return 'update';
    }

    public function createResource($options) : ResourceInterface
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
        $resource = $this->singleResourceProvider->get($configuration, $repository);
        if ($resource === null) {
            throw new NotFoundHttpException();
        }
        return $resource;
    }

    protected function save($options)
    {
        $configuration = $this->getRequestConfiguration($options);

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->resourceManager->update($this->resource, $configuration->getStateMachineTransition(), $configuration->getStateMachineGraph());

        $event = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
    }

    protected function initialize($options)
    {
        $configuration = $this->getRequestConfiguration($options);

        $event = $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::UPDATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'form_delete' => null,
            'form_delete_parameters' => [],
            'label' => 'label.edit',
        ]);
    }

    protected function getFormAction($options): string
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    protected function getFormActionParameters($option): array
    {
        return ['id' => $this->resource->getId()];
    }

    protected function createSecondaryActions($options): array
    {
        $metadata = $this->getMetadata($options);
        $requestConfiguration = $this->getRequestConfiguration($options);

        $formDelete = $this->util->mergeConfig([
            sprintf('%s_%s_delete', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['form_delete'],
            $this->util->getViewerOption('form.delete', $requestConfiguration)
        ]);

        $formDeleteParameters = $this->util->mergeConfigArray([
            $options['form_delete_parameters'],
            $this->util->getViewerOption('form.delete_parameters', $requestConfiguration)
        ]);

        return [
            'delete' => [
                'type' => 'delete',
                'route' => $formDelete,
                'route_parameters' => $formDeleteParameters,
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'delete')
            ]
        ];
    }

    protected function getRedirectRoute($options): ?string
    {
        return null;
    }
}
