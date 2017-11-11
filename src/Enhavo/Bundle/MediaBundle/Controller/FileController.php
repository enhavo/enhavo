<?php

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Security\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(MediaManager $mediaManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->mediaManager = $mediaManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function showAction(Request $request)
    {
        $file = $this->getFile($request);

        if(!$this->authorizationChecker->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = new Response();
        $response->setContent($file->getContent()->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());
        return $response;
    }

    /**
     * @param Request $request
     * @return FileInterface
     */
    private function getFile(Request $request)
    {
        $id = $request->get('id');
        $filename = $request->get('filename');
        $shortChecksum = $request->get('shortMd5Checksum');

        $file = $this->mediaManager->findOneBy([
            'id' => $id,
            'filename' => $filename
        ]);

        if($file === null) {
            throw $this->createNotFoundException();
        }

        if($shortChecksum != substr($file->getMd5Checksum(), 0, 6)) {
            throw $this->createNotFoundException();
        }

        return $file;
    }

    public function downloadAction(Request $request)
    {
        $filename = $request->get('filename');

        $response = $this->showAction($request);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function showFormatAction(Request $request)
    {
        $id = $request->get('id');
        $filename = $request->get('filename');
        $shortChecksum = $request->get('shortMd5Checksum');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'id' => $id
        ]);

        if($file === null) {
            throw $this->createNotFoundException();
        }

        if($shortChecksum != substr($file->getMd5Checksum(), 0, 6)) {
            throw $this->createNotFoundException();
        }

        $formatFile = $this->mediaManager->getFormat($file, $format);

        if(pathinfo($formatFile->getFilename())['filename'] !== pathinfo($filename)['filename']) {
            throw $this->createNotFoundException();
        }

        if(!$this->authorizationChecker->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = new Response();
        $response->setContent($formatFile->getContent()->getContent());
        $response->headers->set('Content-Type', $formatFile->getMimeType());
        return $response;
    }

    public function downloadFormatAction(Request $request)
    {
        $filename = $request->get('filename');

        $response = $this->showFormatAction($request);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function resolveAction(Request $request)
    {
        $token = $request->get('token');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        return $this->redirectToRoute('enhavo_media_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }

    public function resolveFormatAction(Request $request)
    {
        $token = $request->get('token');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        return $this->redirectToRoute('enhavo_media_format', [
            'id' => $file->getId(),
            'format' => $format,
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }

    /**
     * @deprecated this function will be removed on 0.4
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function showCompatibleAction(Request $request)
    {
        $id = $request->get('id');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'id' => $id
        ]);

        if($format) {
            return $this->redirectToRoute('enhavo_media_format', [
                'id' => $file->getId(),
                'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
                'filename' => $file->getFilename(),
                'format' => $format
            ]);
        }

        return $this->redirectToRoute('enhavo_media_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }

    /**
     * @deprecated this function will be removed on 0.4
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function downloadCompatibleAction(Request $request)
    {
        $id = $request->get('id');

        $file = $this->mediaManager->findOneBy([
            'id' => $id
        ]);

        return $this->redirectToRoute('enhavo_media_download', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }
}