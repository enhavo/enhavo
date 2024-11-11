<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileReplaceEndpointType extends AbstractEndpointType
{
    use FileTrait;

    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly MediaManager $mediaManager,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $file = $this->getFileByToken($request);
        $uploadedFile = $this->getUploadedFile($request);

        $errors = $this->getErrors($options, $uploadedFile);
        if (count($errors)) {
            $response = new JsonResponse([
                'success' => false,
                'errors' => $errors,
            ]);
            $context->setResponse($response);
        }

        $newFile = $this->fileFactory->createFromUploadedFile($uploadedFile);

        $file->setContent($newFile->getContent());
        $file->setMimeType($newFile->getMimeType());
        $file->setBasename($newFile->getBasename());

        $this->mediaManager->saveFile($file);

        $context->setResponse($this->createFileResponse($file));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['media_upload']
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file_replace';
    }
}
