<?php
/**
 * ImageCropperViewer.php
 *
 * @since 11/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\Type\AppViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageCropperViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ViewUtil $util,
        private ActionManager $actionManager,
        private FlashBag $flashBag,
        private ImageCropperManager $imageCropperManager,
        private MediaManager $mediaManager,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getName(): ?string
    {
        return 'image_cropper';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::UPDATE);
    }

    /**
     * {@inheritdoc}
     */
    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->getRequestConfiguration($options);

        $actions = $this->util->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->util->getViewerOption('actions', $configuration)
        ]);

        $data['format'] = $this->createFormatData($options['format']);
        $data['messages'] = $this->getFlashMessages();
        $data['actions'] = $this->actionManager->createActionsViewData($actions);
    }

    private function getFlashMessages()
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

    private function createFormatData(Format $format)
    {
        $url = $this->urlGenerator->generate($format->getFile());
        $parameters = $format->getParameters();
        $ratio = $this->imageCropperManager->getFormatRatio($format);

        if(is_array($parameters)) {
            if(array_key_exists('cropHeight', $parameters) &&
                array_key_exists('cropWidth', $parameters) &&
                array_key_exists('cropX', $parameters) &&
                array_key_exists('cropY', $parameters))
            {
                return [
                    'height' => $parameters['cropHeight'],
                    'width' => $parameters['cropWidth'],
                    'x' => $parameters['cropX'],
                    'y' =>$parameters['cropY'],
                    'ratio' => $ratio,
                    'url' => $url
                ];
            }
        }

        return [
            'height' => null,
            'width' => null,
            'x' => null,
            'y' => null,
            'ratio' => $ratio,
            'url' => $url
        ];
    }

    protected function createActions($options)
    {
        $default = [
            'save' => [
                'type' => 'save'
            ],
            'zoom-in' => [
                'type' => 'event',
                'label' => 'media.image_cropper.label.tooltip_zoom_in',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'add',
                'event' => 'image-cropper-zoom-in'
            ],
            'zoom-out' => [
                'type' => 'event',
                'label' => 'media.image_cropper.label.tooltip_zoom_out',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'remove',
                'event' => 'image-cropper-zoom-out'
            ],
            'reset' => [
                'type' => 'event',
                'label' => 'media.image_cropper.label.tooltip_reset',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'reply',
                'event' => 'image-cropper-reset'
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'application' => '@enhavo/media/image-cropper/ImageCropperApp',
            'component' => '@enhavo/media/image-cropper/components/ImageCropperComponent.vue',
            'template' => 'admin/view/image-cropper.html.twig',
            'actions' => [],
            'label' => 'media.image_cropper.label.image_cropper',
            'translation_domain' => 'EnhavoMediaBundle',
            'translations' => true,
            'routes' => true,
        ]);
        $optionsResolver->setRequired('request_configuration');
        $optionsResolver->setRequired('format');
    }
}
