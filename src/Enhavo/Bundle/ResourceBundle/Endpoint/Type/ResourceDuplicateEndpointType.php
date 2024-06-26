<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Controller\DuplicateResourceFactoryInterface;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResourceDuplicateEndpointType extends AbstractEndpointType
{
    use ResourceMetadataHelperTrait;

    protected ResourceInterface $resource;

    public function __construct(
        private ViewUtil $util,
        private RouterInterface $router,
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        private TranslatorInterface $translator,
        private ResourceManager $resourceManager,
        private SingleResourceProviderInterface $singleResourceProvider,
        private EventDispatcherInterface $eventDispatcher,
        private DuplicateResourceFactoryInterface $duplicateResourceFactory,
    ) {}

    public static function getName(): ?string
    {
        return 'duplicate';
    }

    public function init($options)
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::CREATE);
        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
        $resource = $this->singleResourceProvider->get($configuration, $repository);
        if ($resource === null) {
            throw new NotFoundHttpException();
        }
        $this->resource = $resource;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);
        $metadata = $this->getMetadata($options);

        $factory = $this->resourceManager->getFactory($metadata->getApplicationName(), $metadata->getName());
        $newResource = $this->duplicateResourceFactory->duplicate($configuration, $factory, $this->resource);

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->resourceManager->create($newResource);

        $event = $this->eventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->requestStack->getSession()->getFlashBag()->add('success', $this->translator->trans('form.message.success', [], 'EnhavoAppBundle'));

        $route = $configuration->getRedirectRoute(null);

        return new RedirectResponse($this->router->generate($route, [
            'id' => $newResource->getId(),
            'tab' => $request->get('tab'),
            'view_id' => $request->get('view_id')
        ]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('request_configuration');
    }
}
