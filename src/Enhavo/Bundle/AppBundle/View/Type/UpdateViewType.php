<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Gedmo\Tree\RepositoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UpdateViewType extends AbstractViewType
{
    public function __construct(
        private array $formThemes,
        private ActionManager $actionManager,
        private FlashBag $flashBag,
        private ViewUtil $util,
        private RouterInterface $router,
        private TranslatorInterface $translator,
        private NormalizerInterface $normalizer,
        private EntityManagerInterface $em,
    ) {}

    public static function getName(): ?string
    {
        return 'update';
    }

    public static function getParentType(): ?string
    {
        return CreateViewType::class;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, ViewData $templateData)
    {
        $this->util->updateRequest();
        $configuration = $this->util->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);

        $resource = $options['resource'];
        if ($resource === null) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $configuration->getMetadata()->getHumanizedName()));
        }

        $resourceFormFactory = $options['resource_form_factory'];

        /** @var Form $form */
        $form = $resourceFormFactory->create($configuration, $resource);
        $form->handleRequest($request);

        $eventDispatcher = $options['event_dispatcher'];
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']) && $form->isSubmitted()) {
            if ($form->isValid()) {
                $resource = $form->getData();
                $eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->em->flush();
                $eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->flashBag->add('success', $this->translator->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $request->get('_route');
                return new RedirectResponse($this->router->generate($route, [
                    'id' => $resource->getId(),
                    'tab' => $request->get('tab'),
                    'view_id' => $request->get('view_id')
                ]));
            } else {
                $this->flashBag->add('error', $this->translator->trans('form.message.error', [], 'EnhavoAppBundle'));
                foreach($form->getErrors() as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }
            }
        }
        $viewData['messages'] = array_merge($viewData['messages'], $this->getFlashMessages());
        $templateData['form'] = $form->createView();
    }

    private function getFlashMessages(): array
    {
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach($types as $type) {
            foreach($this->flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => is_array($message) ? $message['message'] : $message,
                    'type' => $type
                ];
            }
        }
        return $messages;
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

    public function createViewData($options, ViewData $data)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        /** @var ResourceInterface $resource */
        $resource = $options['resource'];

        $configuration = $this->util->getRequestConfiguration($options);

        $data['form_action'] = $this->util->mergeConfig([
            sprintf('%s_%s_update', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['form_action'],
            $this->util->getViewerOption('form.action', $configuration)
        ]);

        $data['form_action_parameters'] = $this->util->mergeConfigArray([
            [ 'id' => $resource->getId() ],
            $options['form_action_parameters'],
            $this->util->getViewerOption('form.action_parameters', $configuration)
        ]);

        $actionsSecondary = $this->util->mergeConfigArray([
            $this->createActionsSecondary($options, $configuration, $resource),
            $options['actions_secondary'],
            $this->util->getViewerOption('actions_secondary', $configuration)
        ]);

        $data['actionsSecondary'] = $this->actionManager->createActionsViewData($actionsSecondary, $options['resource']);

        $resourceData = $this->getResourceData($configuration, $options);
        if ($resourceData) {
            $data['resource'] = $resourceData;
        }
    }

    private function createActionsSecondary($options, $requestConfiguration, $resource)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $formDelete = $this->util->mergeConfig([
            sprintf('%s_%s_delete', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['form_delete'],
            $this->util->getViewerOption('form.delete', $requestConfiguration)
        ]);

        $formDeleteParameters = $this->util->mergeConfigArray([
            $options['form_delete_parameters'],
            $this->util->getViewerOption('form.delete_parameters', $requestConfiguration)
        ]);

        $default = [
            'delete' => [
                'type' => 'delete',
                'route' => $formDelete,
                'route_parameters' => $formDeleteParameters,
                'permission' => $this->util->getRoleNameByResourceName($metadata->getApplicationName(), $this->util->getUnderscoreName($metadata), 'delete')
            ]
        ];

        return $default;
    }

    private function getResourceData(RequestConfiguration $requestConfiguration, array $options)
    {
        $resourceData = null;
        $serializationGroups = $requestConfiguration->getSerializationGroups();
        if($serializationGroups && $options['resource']) {
            $resourceData = $this->normalizer->normalize($options['resource'], null, ['groups' => $serializationGroups]);
        }
        return $resourceData;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'form_delete' => null,
            'form_delete_parameters' => [],
            'label' => 'label.edit'
        ]);

        $optionsResolver->setRequired('single_resource_provider');

        $optionsResolver->setNormalizer('resource', function(Options $options, $value) {
            $configuration = $this->util->getRequestConfiguration($options);

            if ($value === null) {
                return $this->findResource($configuration, $options);
            }

            return $value;
        });
    }
}
