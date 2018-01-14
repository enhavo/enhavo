<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 19:46
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageCropperController extends Controller
{
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

        $parameters['height'] = $height;
        $parameters['width'] = $width;
        $parameters['x'] = $x;
        $parameters['y'] = $y;

        $format->setParameters($parameters);
        $this->container->get('doctrine.orm.entity_manager')->flush();

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
        $imageCropperManager = $this->get('enhavo_media.media.image_cropper_manager');
        $ratio = $imageCropperManager->getFormatRatio($format);

        if(is_array($parameters)) {
            if(array_key_exists('height', $parameters) &&
                array_key_exists('width', $parameters) &&
                array_key_exists('x', $parameters) &&
                array_key_exists('y', $parameters))
            {
                return new JsonResponse([
                    'height' => $parameters['height'],
                    'width' => $parameters['width'],
                    'x' => $parameters['x'],
                    'y' =>$parameters['y'],
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

        $file = $this->get('enhavo_media.media.media_manager')->findOneBy([
            'token' => $token
        ]);

        $format = $this->get('enhavo_media.media.media_manager')->getFormat($file, $format);
        return $format;
    }
}