<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileUploadEndpointType extends AbstractEndpointType
{
    use FileTrait;

    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly MediaManager $mediaManager,
        private readonly ValidatorInterface $validator,
        private readonly array $validationGroups,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $storedFiles = [];
        foreach ($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var $uploadedFile UploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    $errors = $this->getErrors($uploadedFile);
                    if (count($errors)) {
                        $response = new JsonResponse([
                            'success' => false,
                            'errors' => $errors,
                        ]);
                        $context->setResponse($response);

                    }
                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);

                    $file->setGarbage($options['garbage']);
                    $file->setLibrary($options['library']);

                    $this->mediaManager->saveFile($file);
                    $storedFiles[] = $file;

                } catch(StorageException $exception) {
                    foreach($storedFiles as $file) {
                        $this->mediaManager->deleteFile($file);
                    }
                }
            }
        }

        $context->setResponse($this->createFileResponse($storedFiles));
    }

    private function getErrors(UploadedFile $uploadedFile): array
    {
        $result = [];

        $errors = $this->validator->validate($uploadedFile, null, $this->validationGroups);
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $result[] = $error->getMessage();
        }

        return $result;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'library' => false,
            'garbage' => true,
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file_upload';
    }
}
