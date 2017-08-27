<?php

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Security\AuthorizationChecker\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

    public function showAction($id, $filename)
    {
        $file = $this->mediaManager->findOneBy([
            'id' => $id,
            'filename' => $filename
        ]);

        if(!$this->authorizationChecker->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = new Response();
        $response->setContent($file->getContent()->getContent());
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', sprintf('filename="%s"', $filename));
        return $response;
    }

    public function downloadAction($id, $filename)
    {
        $response = $this->showAction($id, $filename);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function showFormatAction($id, $filename, $format)
    {
        $file = $this->mediaManager->findOneBy([
            'id' => $id
        ]);

        if(!$this->authorizationChecker->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $formatFile = $this->mediaManager->getFormat($file, $format);
        if($formatFile->getFilename() !== $filename) {
            throw $this->createNotFoundException();
        }

        $response = new Response();
        $response->setContent($formatFile->getContent()->getContent());
        $response->headers->set('Content-Type', $formatFile->getMimeType());
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function downloadFormatAction($id, $filename, $format)
    {
        $response = $this->showFormatAction($id, $filename, $format);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }
}
