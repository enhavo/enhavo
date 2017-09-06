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
        $response->headers->set('Content-Disposition', sprintf('filename="%s"', $file->getFilename()));
        return $response;
    }

    /**
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

        if($shortChecksum != substr($file->getMd5Checksum(), 0, 6)) {
            throw $this->createNotFoundException();
        }

        $formatFile = $this->mediaManager->getFormat($file, $format);
        if($formatFile->getFilename() !== $filename) {
            throw $this->createNotFoundException();
        }

        if(!$this->authorizationChecker->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = new Response();
        $response->setContent($formatFile->getContent()->getContent());
        $response->headers->set('Content-Type', $formatFile->getMimeType());
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
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
            'md5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }
}