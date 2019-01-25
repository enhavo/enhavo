<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 19:46
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
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
     * ImageCropperController constructor.
     * @param ImageCropperManager $imageCropperManager
     * @param MediaManager $mediaManager
     */
    public function __construct(ImageCropperManager $imageCropperManager, MediaManager $mediaManager)
    {
        $this->imageCropperManager = $imageCropperManager;
        $this->mediaManager = $mediaManager;
    }
    
    public function cropAction(Request $request)
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

        return new JsonResponse([
            'height' => $height,
            'width' => $width,
            'x' => $x,
            'y' => $y,
        ]);
    }
    
    public function dataAction(Request $request)
    {
        $format = $this->getFormat($request);
        $parameters = $format->getParameters();
        $ratio = $this->imageCropperManager->getFormatRatio($format);

        if(is_array($parameters)) {
            if(array_key_exists('cropHeight', $parameters) &&
                array_key_exists('cropWidth', $parameters) &&
                array_key_exists('cropX', $parameters) &&
                array_key_exists('cropY', $parameters))
            {
                return new JsonResponse([
                    'height' => $parameters['cropHeight'],
                    'width' => $parameters['cropWidth'],
                    'x' => $parameters['cropX'],
                    'y' =>$parameters['cropY'],
                    'ratio' => $ratio
                ]);
            }
        }

        return new JsonResponse([
            'height' => null,
            'width' => null,
            'x' => null,
            'y' => null,
            'ratio' => $ratio
        ]);
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
