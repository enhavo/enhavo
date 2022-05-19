<?php
/**
 * PreviewViewer.php
 *
 * @since 12/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PreviewViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ViewUtil $util,
        private RouterInterface $router,
        private ActionManager $actionManager,
        private RequestStack $requestStack,
        private ResourceManager $resourceManager,
        private SingleResourceProviderInterface $singleResourceProvider,
    ) {}

    public static function getName(): string|null
    {
        return 'preview';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function createViewData($options, ViewData $data)
    {
        $metadata = $this->getMetadata($options);
        $configuration = $this->getRequestConfiguration($options);

        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());


        $request = $this->requestStack->getMainRequest();
        $resource = null;
        if ($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            $resource = $this->singleResourceProvider->get($configuration, $repository);
        }

        $parameters = [];
        if ($resource instanceof ResourceInterface) {
            $parameters['id'] = $resource->getId();
        }

        $data['url'] = $this->router->generate($this->getResourcePreviewUrl($options), $parameters);
        $data['inputs'] = [];
        $data['actions'] = $this->actionManager->createActionsViewData($this->createActions());
    }

    private function getResourcePreviewUrl($options)
    {
        $metadata = $this->getMetadata($options);
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        return sprintf('%s_%s_resource_preview', $metadata->getApplicationName(), $name);
    }

    private function createActions()
    {
        return [
            'desktop' => [
                'type' => 'event',
                'label' => 'Desktop',
                'icon' => 'desktop_windows',
                'event' => 'desktop'
            ],
            'mobile' => [
                'type' => 'event',
                'label' => 'Mobile',
                'icon' => 'phone_iphone',
                'event' => 'mobile'
            ],
            'tablet' => [
                'type' => 'event',
                'label' => 'Tablet',
                'icon' => 'tablet_mac',
                'event' => 'tablet'
            ],
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'application' => '@enhavo/app/preview/PreviewApp',
            'component' => '@enhavo/app/preview/components/ApplicationComponent.vue',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => 'label.preview',
            'resource' => null,
            'translations' => true,
            'routes' => true,
        ]);

        $optionsResolver->setRequired('request_configuration');
    }
}
