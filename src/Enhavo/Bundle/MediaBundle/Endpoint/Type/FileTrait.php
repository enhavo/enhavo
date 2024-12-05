<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;

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
                    'basename' => $file->getBasename(),
                    'extension' => $file->getExtension(),
                    'mimeType' => $file->getMimeType(),
                    'checksum' => $file->getChecksum(),
                    'shortChecksum' => $file->getShortChecksum(),
                ];
            }
        }

        if ($files instanceof FileInterface) {
            $data = [
                'id' => $files->getId(),
                'token' => $files->getToken(),
                'filename' => $files->getFilename(),
                'basename' => $files->getBasename(),
                'extension' => $files->getExtension(),
                'mimeType' => $files->getMimeType(),
                'checksum' => $files->getChecksum(),
                'shortChecksum' => $files->getShortChecksum(),
            ];
        }

        return new JsonResponse($data);
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

    protected function getErrors($options, UploadedFile $uploadedFile): array
    {
        $result = [];

        $errors = $this->validator->validate($uploadedFile, null, $options['validation_groups']);
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $result[] = $error->getMessage();
        }

        return $result;
    }
}
