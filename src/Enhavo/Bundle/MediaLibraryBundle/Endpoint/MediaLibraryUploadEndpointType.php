<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaLibraryUploadEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly MediaManager $mediaManager,
        private readonly FileFactory $fileFactory,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data['success'] = true;
        $data['errors'] = [];

        $storedFiles = [];
        foreach($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var $uploadedFile UploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    $errors = $this->getErrors($uploadedFile);
                    if (count($errors)) {
                        $data['success'] = false;
                        $data['errors'] = $errors;
                        $context->setStatusCode(400);
                        return;
                    }
                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);
                    $file->setGarbage(false);
                    $file->setLibrary(true);
                    $this->mediaManager->saveFile($file);
                    $storedFiles[] = $file;

                } catch(StorageException $exception) {
                    foreach ($storedFiles as $file) {
                        $this->mediaManager->deleteFile($file);
                    }
                }
            }
        }
    }

    private function getErrors(UploadedFile $uploadedFile): array
    {
        $result = [];

        $errors = $this->validator->validate($uploadedFile, null);
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $result[] = $error->getMessage();
        }

        return $result;
    }

    public static function getName(): ?string
    {
        return 'media_library_upload';
    }
}
