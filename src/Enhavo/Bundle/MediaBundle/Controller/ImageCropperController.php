<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 19:46
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageCropperController extends AbstractController
{
    /**
     * @var ImageCropperManager
     */
    private $imageCropperManager;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    /**
     * ImageCropperController constructor.
     * @param ImageCropperManager $imageCropperManager
     * @param MediaManager $mediaManager
     * @param ViewFactory $viewFactory
     * @param ViewHandler $viewHandler
     */
    public function __construct(
        ImageCropperManager $imageCropperManager,
        MediaManager $mediaManager,
        ViewFactory $viewFactory,
        ViewHandler $viewHandler
    ) {
        $this->imageCropperManager = $imageCropperManager;
        $this->mediaManager = $mediaManager;
        $this->viewFactory = $viewFactory;
        $this->viewHandler = $viewHandler;
    }

    public function indexAction(Request $request)
    {
        $format = $this->getFormat($request);

        if($request->getMethod() == Request::METHOD_POST) {
            $this->cropFormat($request);
        }

        $view = $this->viewFactory->create('image_cropper', [
            'format' => $format,
        ]);

        return $this->viewHandler->handle($view);
    }
    
    private function cropFormat(Request $request)
    {
        $height = intval($request->get('height'));
        $width = intval($request->get('width'));
        $x = intval($request->get('x'));
        $y = intval($request->get('y'));

        $format = $this->getFormat($request);
        $parameters = $format->getParameters();
        if(!is_array($parameters)) {
            $parameters = [];
        }

        $parameters['cropHeight'] = $height;
        $parameters['cropWidth'] = $width;
        $parameters['cropX'] = $x;
        $parameters['cropY'] = $y;

        $format->setParameters($parameters);
        $this->container->get('doctrine.orm.entity_manager')->flush();

        $formatManager = $this->get('enhavo_media.media.format_manager');
        $formatManager->applyFormat($format->getFile(), $format->getName(), $parameters);
    }

    /**
     * @param Request $request
     * @return \Enhavo\Bundle\MediaBundle\Model\FormatInterface|null
     */
    private function getFormat(Request $request)
    {
        $token = $request->get('token');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        $format = $this->mediaManager->getFormat($file, $format);
        return $format;
    }
}
