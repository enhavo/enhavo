<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Controller\AppEventDispatcher;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ResourceDeleteEndpointType extends AbstractEndpointType
{
    use ResourceMetadataHelperTrait;

    protected ResourceInterface $resource;

    public function __construct(
        private ViewUtil $util,
        private RouterInterface $router,
        private EntityManagerInterface $em,
        private FlashHelperInterface $flashHelper,
        private ResourceManager $resourceManager,
        private SingleResourceProviderInterface $singleResourceProvider,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public static function getName(): ?string
    {
        return 'delete';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function init($options)
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);
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

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->resourceManager->delete($this->resource);

        $event = $this->eventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $this->flashHelper->addFlashFromEvent($configuration, $event);
    }

    public function createViewData($options, ViewData $data)
    {
        $data['messages'] = $this->util->getFlashMessages();
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData['resource'] = $this->resource;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/app/delete/DeleteApp',
            'component' => '@enhavo/app/components/delete/DeleteComponent.vue',
        ]);

        $optionsResolver->setRequired('request_configuration');
    }
}
