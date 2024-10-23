<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileReplaceEndpointType extends AbstractEndpointType
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
        $file = $this->getFileByToken($request);
        $uploadedFile = $this->getUploadedFile($request);

        $newFile = $this->fileFactory->createFromUploadedFile($uploadedFile);

        $file->setContent($newFile->getContent());
        $file->setMimeType($newFile->getMimeType());
        $file->setFilename($newFile->getFilename());
        $file->setExtension($newFile->getExtension());

        $this->mediaManager->saveFile($file);

        $context->setResponse($this->createFileResponse($file));
    }

    public static function getName(): ?string
    {
        return 'media_file_replace';
    }
}
