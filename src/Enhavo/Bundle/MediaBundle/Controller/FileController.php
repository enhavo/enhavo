<?php

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class FileController extends Controller
{
    /**
     * @var FileService
     */
    protected $fileManager;

    public function __construct(FileService $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function showAction($id, $width, $height, $filename)
    {
        if ($filename === null) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $file = $this->fileManager->getFileById($id);
        } else {
            $file = $this->fileManager->getFileByIdAndName($id, $filename);
        }

        if ($file === null) {
            throw $this->createNotFoundException('file not found');
        }

        if ($width) {
            return $this->fileManager->getCustomImageSizeResponse($file, $width, $height);
        }
        return $this->fileManager->getResponse($file);
    }

    public function uploadAction(Request $request)
    {
        return $this->container->get('enhavo_media.file_service')->upload($request);
    }

    public function replaceAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->get('enhavo_media.file_service')->replaceFile($id, $request);
    }

    public function downloadAction($id, $filename)
    {
        if ($filename === null) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $file = $this->fileManager->getFileById($id);
        } else {
            $file = $this->fileManager->getFileByIdAndName($id, $filename);
        }

        if ($file === null) {
            throw $this->createNotFoundException('file not found');
        }

        return $this->fileManager->getResponse($file, true);
    }

    public function showFormatAction(Request $request)
    {
        $id = $request->get('id');
        $format = $request->get('format');
        $filename = $request->get('filename');

        if ($filename === null) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $file = $this->fileManager->getFileById($id);
        } else {
            $file = $this->fileManager->getFileByIdAndName($id, $filename);
        }

        if ($file === null) {
            throw $this->createNotFoundException('file not found');
        }
        
        $httpFile = $this->fileManager->getFormatFile($file, $format);

        $response = new BinaryFileResponse($httpFile->getPathname());
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', 'filename="' . $file->getFilename() . '"');
        
        return $response;
    }
}
