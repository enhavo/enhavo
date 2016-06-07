<?php

namespace Enhavo\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FileController extends Controller
{
    public function showAction($id,$width,$height)
    {
        if($width) {
            return $this->container->get('enhavo_media.file_service')->getCustomImageSizeResponse($id,$width,$height);
        }
        return $this->container->get('enhavo_media.file_service')->getResponse($id);
    }

    public function uploadAction(Request $request)
    {
        return $this->container->get('enhavo_media.file_service')->upload($request);
    }

    public function downloadAction($id)
    {
        return $this->container->get('enhavo_media.file_service')->getResponse($id, true);
    }
}
