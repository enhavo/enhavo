<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait FileTrait
{
    protected function createFileResponse(array|FileInterface $files): Response
    {
        $data = null;

        if (is_array($files)) {
            $data = [];
            /** @var FileInterface $file */
            foreach($files as $file) {
                $data[] = [
                    'id' => $file->getId(),
                    'token' => $file->getToken(),
                    'filename' => $file->getFilename(),
                    'extension' => $file->getExtension(),
                    'mimeType' => $file->getMimeType(),
                    'md5Checksum' => $file->getMd5Checksum()
                ];
            }
        }

        if ($files instanceof FileInterface) {
            $data = [
                'id' => $files->getId(),
                'token' => $files->getToken(),
                'filename' => $files->getFilename(),
                'extension' => $files->getExtension(),
                'mimeType' => $files->getMimeType(),
                'md5Checksum' => $files->getMd5Checksum()
            ];
        }

        return new JsonResponse($data);
    }

    protected function getFileByToken(Request $request): FileInterface
    {
        $token = $request->get('token');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        if (!$file) {
            throw $this->createNotFoundException();
        }

        return $file;
    }

    protected function getUploadedFile(Request $request): UploadedFile
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('File not in request');
        }

        if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
            throw new UploadException('Error in file upload');
        }

        return $uploadedFile;
    }
}
