<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\MediaBundle\Http\FileRangeResponse;
use Enhavo\Bundle\MediaBundle\Http\FileResponse;
use Enhavo\Bundle\MediaBundle\Model\FileContentInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

trait FileContentTrait
{
    private function createFileResponse(FileContentInterface $file, Request $request): Response
    {
        $path = $file->getContent()->getFilePath();

        if (!file_exists($path)) {
            $this->mediaManager->handleFileNotFound($file);
        }

        $fileSize = filesize($path);

        if ($request->headers->get('Range')) {
            $rangeHeader = $request->headers->get('Range');

            $length = $fileSize;
            $start = 0;
            $end = $length - 1;

            if (preg_match('/bytes=(\d*)-(\d*)/', $rangeHeader, $matches)) {
                $start = ('' !== $matches[1]) ? intval($matches[1]) : 0;
                $end = ('' !== $matches[2]) ? intval($matches[2]) : $end;
            }

            $response = new FileRangeResponse($file, $start, $end);
        } elseif (!$this->streamingDisabled && $this->streamingThreshold < $fileSize) {
            $response = new StreamedResponse(function () use ($file): void {
                $outputStream = fopen('php://output', 'wb');
                $fileStream = fopen($file->getContent()->getFilePath(), 'r');
                stream_copy_to_stream($fileStream, $outputStream);
            });
            $response->headers->set('Content-Type', $file->getMimeType());
        } elseif ('attachment' === $request->get('disposition')) {
            $response = new FileResponse($file);
            $response->headers->set('Content-Type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file->getBasename()));
        } else {
            $response = new FileResponse($file);
            if ($file->getMimeType()) {
                $response->headers->set('Content-Type', $file->getMimeType());
            }
        }

        return $response;
    }

    protected function handleCaching(Response $response): void
    {
        if ($response instanceof StreamedResponse) {
            // StreamedResponse will return an empty response if cached via http cache, so we prevent caching
            $response->setPrivate();

            return;
        }

        $maxAge = $this->maxAge;
        if ($maxAge) {
            $this->setMaxAge($response, $maxAge);
        }
    }

    private function setMaxAge(Response $response, $maxAge): void
    {
        $response
            ->setExpires($this->getDateInSeconds($maxAge))
            ->setMaxAge($maxAge)
            ->setPublic();

        $response->headers->add([
            AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER => true,
        ]);
    }

    private function getDateInSeconds($seconds): \DateTime
    {
        $date = new \DateTime();
        $date->modify(sprintf('+%s seconds', $seconds));

        return $date;
    }
}
