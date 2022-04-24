<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Controller\AppEventDispatcher;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class DeleteViewType extends AbstractViewType
{
    public function __construct(
        private ViewUtil $util,
        private RouterInterface $router,
        private EntityManagerInterface $em,
        private FlashHelperInterface $flashHelper,
    ) {}

    public static function getName(): ?string
    {
        return 'delete';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $this->util->updateRequest();
        $configuration = $this->util->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::DELETE);

        $resource = $options['resource'];
        if ($resource === null) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $configuration->getMetadata()->getHumanizedName()));
        }

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $options['event_dispatcher'];
        /** @var AppEventDispatcher $appEventDispatcher */
        $appEventDispatcher = $options['app_event_dispatcher'];

        $repository = $options['repository'];

        $appEventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $resource);
        $event = $eventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $resource);

        if ($event->isStopped()) {
            $this->flashHelper->addFlashFromEvent($configuration, $event);

            $eventResponse = $event->getResponse();
            if (null !== $eventResponse) {
                return $eventResponse;
            }

            $route = $request->get('_route');
            return new RedirectResponse($this->router->generate($route, [
                'id' => $resource->getId(),
                'tab' => $request->get('tab'),
                'view_id' => $request->get('view_id')
            ]));
        }

        $repository->remove($resource);
        $this->em->flush();

        $appEventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $resource);
        $eventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $resource);

        $viewData['messages'] = array_merge($viewData['messages'], $this->util->getFlashMessages());

        return null;
    }

    public function createViewData($options, ViewData $data)
    {
//        $configuration = $this->util->getRequestConfiguration($options);
//
//        $label = $this->util->mergeConfig([
//            $options['label'],
//            $this->util->getViewerOption('label', $configuration)
//        ]);
//
//        $viewerOptions = $configuration->getViewerOptions();
        $data['messages'] = $this->util->getFlashMessages();
//        $data['view'] = [
//            'id' => $this->getViewId(),
//            'label' => $this->translator->trans($label, [], $viewerOptions['translation_domain'])
//        ];
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData->set('resource', $options['resource']);
    }

    public function findResource(RequestConfiguration $configuration, $options): ?ResourceInterface
    {
        /** @var SingleResourceProvider $singleResourceProvider */
        $singleResourceProvider = $options['single_resource_provider'];

        /** @var RepositoryInterface $repository */
        $repository = $options['repository'];

        if (null === $resource = $singleResourceProvider->get($configuration, $repository)) {
            return null;
        }

        return $resource;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/app/delete/DeleteApp',
            'component' => '@enhavo/app/delete/components/DeleteComponent.vue',
            'request_configuration' => null,
            'event_dispatcher' => null,
            'app_event_dispatcher' => null,
            'resource' => null,
        ]);

        $optionsResolver->setRequired([
            'single_resource_provider',
            'repository'
        ]);

        $optionsResolver->setNormalizer('resource', function(Options $options, $value) {
            $configuration = $this->util->getRequestConfiguration($options);

            if ($value === null) {
                return $this->findResource($configuration, $options);
            }

            return $value;
        });
    }
}
