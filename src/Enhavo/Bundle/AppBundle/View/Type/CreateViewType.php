<?php

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Grid\GridManager;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractResourceFormType;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateViewType extends AbstractResourceFormType
{
    public function __construct(
        array $formThemes,
        ActionManager $actionManager,
        FlashBag $flashBag,
        private ViewUtil $util,
        RouterInterface $router,
        TranslatorInterface $translator,
        private ResourceManager $resourceManager,
        GridManager $gridManager,
        ResourceFormFactoryInterface $resourceFormFactory,
        NormalizerInterface $normalizer,
        EventDispatcherInterface $eventDispatcher,
        private NewResourceFactoryInterface $newResourceFactory,
    ) {
        parent::__construct($formThemes, $actionManager, $flashBag, $util, $router, $translator, $resourceManager, $gridManager, $resourceFormFactory, $normalizer, $eventDispatcher);
    }

    public static function getName(): ?string
    {
        return 'create';
    }

    protected function createResource($options): ResourceInterface
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::CREATE);
        $factory = $this->resourceManager->getFactory($metadata->getApplicationName(), $metadata->getName());
        return $this->newResourceFactory->create($configuration, $factory);
    }

    protected function save($options)
    {
        $configuration = $this->getRequestConfiguration($options);

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->resourceManager->create($this->resource, $configuration->getStateMachineTransition(), $configuration->getStateMachineGraph());

        $event = $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $this->resource);
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

        $event = $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::CREATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
    }

    protected function getFormAction($options): string
    {
        $metadata = $this->getMetadata($options);
        return sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata));
    }

    protected function getFormActionParameters($option): array
    {
        return [];
    }

    protected function getRedirectRoute($options): ?string
    {
        $configuration = $this->getRequestConfiguration($options);
        return $configuration->getRedirectRoute(null);
    }
}
