<?php
/**
 * UpdateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Grid\GridManager;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractResourceFormType;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UpdateViewType extends AbstractResourceFormType
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
        private SingleResourceProviderInterface $singleResourceProvider,
    ) {
        parent::__construct($formThemes, $actionManager, $flashBag, $util, $router, $translator, $resourceManager, $gridManager, $resourceFormFactory, $normalizer);
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

        $this->resourceManager->update($this->resource, [
            'event_name' => $configuration->getEvent() ?? ResourceActions::UPDATE,
            'application_name' =>  $configuration->getMetadata()->getApplicationName(),
            'entity_name' =>  $configuration->getMetadata()->getName(),
            'transition' => $configuration->getStateMachineTransition(),
            'graph' => $configuration->getStateMachineGraph(),
        ]);
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
