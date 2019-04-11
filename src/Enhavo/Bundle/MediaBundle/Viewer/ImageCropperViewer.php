<?php
/**
 * ImageCropperViewer.php
 *
 * @since 11/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Viewer\ViewerInterface;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Media\UrlResolver;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageCropperViewer implements ViewerInterface
{
    use ContainerAwareTrait;

    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var ImageCropperManager
     */
    private $imageCropperManager;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var UrlResolver
     */
    private $urlResolver;

    /**
     * ImageCropperViewer constructor.
     * @param ActionManager $actionManager
     * @param FlashBag $flashBag
     * @param ImageCropperManager $imageCropperManager
     * @param MediaManager $mediaManager
     * @param UrlResolver $urlResolver
     */
    public function __construct(
        ActionManager $actionManager,
        FlashBag $flashBag,
        ImageCropperManager $imageCropperManager,
        MediaManager $mediaManager,
        UrlResolver $urlResolver
    ) {
        $this->actionManager = $actionManager;
        $this->flashBag = $flashBag;
        $this->imageCropperManager = $imageCropperManager;
        $this->mediaManager = $mediaManager;
        $this->urlResolver = $urlResolver;
    }

    public function getType()
    {
        return 'image_cropper';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = View::create($options['format'], 200);
        $templateVars = [];

        $templateVars['stylesheets'] = ['enhavo/image-cropper'];
        $templateVars['javascripts'] = ['enhavo/image-cropper'];

        $templateVars['translations'] = $this->getTranslations();
        $templateVars['routes'] = $this->getRoutes();

        $data = [];
        $data['actions'] = $this->actionManager->createActionsViewData($this->createActions($options));
        $data['format'] = $this->createFormatData($options['format']);
        $data['view'] = ['view_id' => null];
        $templateVars['data'] = $data;

        $view->setTemplateData($templateVars);
        $view->setTemplate('EnhavoMediaBundle:ImageCropper:index.html.twig');

        return $view;
    }

    private function createFormatData(Format $format)
    {
        $url = $this->urlResolver->getPrivateUrl($format->getFile());
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

    private function createActions($options)
    {
        $default = [
            'save' => [
                'type' => 'save'
            ],
            'move' => [
                'type' => 'event',
                'label' => 'media.image_cropper.label.tooltip_move',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'zoom_out_map',
                'event' => 'image-cropper-move'
            ],
            'crop' => [
                'type' => 'event',
                'label' => 'media.image_cropper.label.tooltip_cropframe',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'crop',
                'event' => 'image-cropper-crop'
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

    private function getRoutes()
    {
        $file = $this->container->getParameter('kernel.project_dir').'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    private function getTranslations()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $dumper = $this->container->get('enhavo_app.translation.translation_dumper');
        $translations = $dumper->getTranslations('javascript', $request->getLocale());
        return $translations;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'javascripts' => [
                'enhavo/image-cropper'
            ],
            'stylesheets' => [
                'enhavo/image-cropper'
            ],
        ]);
        $optionsResolver->setRequired('format');
    }
}
