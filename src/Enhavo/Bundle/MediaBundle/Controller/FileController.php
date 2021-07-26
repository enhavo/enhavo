<?php

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Security\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

class FileController extends ResourceController
{
    /**
     * @return MediaManager
     */
    private function getMediaManager()
    {
        return $this->container->get('enhavo_media.media.media_manager');
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    private function getAuthorizationChecker()
    {
        return $this->container->get('enhavo_media.security.default_authorization_checker');
    }

    public function showAction(Request $request): Response
    {
        $file = $this->getFile($request);

        if(!$this->getAuthorizationChecker()->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = $this->getResponse($file);

        $maxAge = $this->getMaxAge();
        if($maxAge) {
            $this->setMaxAge($response, $maxAge);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return FileInterface
     */
    private function getFile(Request $request): FileInterface
    {
        $id = $request->get('id');
        $filename = $request->get('filename');
        $shortChecksum = $request->get('shortMd5Checksum');

        $file = $this->getMediaManager()->findOneBy([
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

    public function downloadAction(Request $request): Response
    {
        $filename = $request->get('filename');

        $response = $this->showAction($request);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function showFormatAction(Request $request): Response
    {
        $id = $request->get('id');
        $filename = $request->get('filename');
        $shortChecksum = $request->get('shortMd5Checksum');
        $format = $request->get('format');

        $file = $this->getMediaManager()->findOneBy([
            'id' => $id
        ]);

        if($file === null) {
            throw $this->createNotFoundException();
        }

        if($shortChecksum != substr($file->getMd5Checksum(), 0, 6)) {
            throw $this->createNotFoundException();
        }

        $formatFile = $this->getMediaManager()->getFormat($file, $format);

        if(pathinfo($formatFile->getFilename())['filename'] !== pathinfo($filename)['filename']) {
            throw $this->createNotFoundException();
        }

        if(!$this->getAuthorizationChecker()->isGranted($file)) {
            throw $this->createAccessDeniedException();
        }

        $response = $this->getResponse($formatFile);

        $maxAge = $this->getMaxAge();
        if($maxAge) {
            $this->setMaxAge($response, $maxAge);
        }

        return $response;
    }

    /**
     * @param FileInterface|FormatInterface $file
     * @return Response
     */
    private function getResponse($file): Response
    {
        $path = $file->getContent()->getFilePath();

        if (!file_exists($path))  {
            throw $this->createNotFoundException('File not exists, please refresh format');
        }

        $fileSize = filesize($file->getContent()->getFilePath());
        if (!$this->getStreamingDisabled() && $this->getStreamingThreshold() < $fileSize) {
            $response = new StreamedResponse(function () use ($file) {
                $outputStream = fopen('php://output', 'wb');
                $fileStream = fopen($file->getContent()->getFilePath(), 'r');
                stream_copy_to_stream($fileStream, $outputStream);
            });
        } else {
            $response = new Response();
            $content = $file->getContent()->getContent();
            $response->setContent($content);
        }
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Length', $fileSize);

        return $response;
    }

    private function getMaxAge()
    {
        return $this->getParameter('enhavo_media.cache_control.max_age');
    }

    private function getStreamingDisabled()
    {
        return $this->getParameter('enhavo_media.streaming.disabled');
    }

    private function getStreamingThreshold()
    {
        return $this->getParameter('enhavo_media.streaming.threshold');
    }

    private function setMaxAge(Response $response, $maxAge)
    {
        $response
            ->setExpires($this->getDateInSeconds($maxAge))
            ->setMaxAge($maxAge)
            ->setPublic();

        $response->headers->add([
            AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER => true
        ]);
    }

    private function getDateInSeconds($seconds)
    {
        $date = new \DateTime();
        $date->modify(sprintf('+%s seconds', $seconds));
        return $date;
    }

    public function downloadFormatAction(Request $request): Response
    {
        $filename = $request->get('filename');

        $response = $this->showFormatAction($request);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        return $response;
    }

    public function resolveAction(Request $request): Response
    {
        $token = $request->get('token');

        $file = $this->getMediaManager()->findOneBy([
            'token' => $token
        ]);

        return $this->redirectToRoute('enhavo_media_file_show', [
            'id' => $file->getId(),
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }

    public function resolveFormatAction(Request $request): Response
    {
        $token = $request->get('token');
        $format = $request->get('format');

        $file = $this->getMediaManager()->findOneBy([
            'token' => $token
        ]);

        return $this->redirectToRoute('enhavo_media_file_format', [
            'id' => $file->getId(),
            'format' => $format,
            'shortMd5Checksum' => substr($file->getMd5Checksum(), 0, 6),
            'filename' => $file->getFilename(),
        ]);
    }
}
