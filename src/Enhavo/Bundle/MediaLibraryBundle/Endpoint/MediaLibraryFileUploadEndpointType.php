<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Endpoint\Type\FileTrait;
use Enhavo\Bundle\MediaLibraryBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Upload of an admin media field that also lands in the media library: the uploaded file becomes a library
 * item and the field gets a copy linked to that item, like a library select (MediaLibrarySelectEndpointType).
 * A file whose checksum is already in the library reuses that item.
 * The response matches media_file_upload; the copy stays garbage until the submitted form uses it.
 */
class MediaLibraryFileUploadEndpointType extends AbstractEndpointType
{
    use FileTrait;

    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly ItemRepository $itemRepository,
        private readonly MediaLibraryManager $mediaLibraryManager,
        private readonly ResourceManager $resourceManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $files = [];
        foreach ($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var UploadedFile $uploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                $errors = $this->getErrors($options, $uploadedFile);
                if (count($errors)) {
                    $context->setResponse(new JsonResponse(['success' => false, 'errors' => $errors], 400));

                    return;
                }

                $libraryFile = $this->fileFactory->createFromUploadedFile($uploadedFile);
                $item = $this->itemRepository->findByChecksum($libraryFile->getChecksum())[0] ?? null;
                if (!$item instanceof ItemInterface) {
                    $item = $this->mediaLibraryManager->createItem($libraryFile);
                    $this->resourceManager->save($item);
                }

                $file = $this->fileFactory->createFromItem($item);
                $file->setGarbage(true);
                $this->resourceManager->save($file);
                $files[] = $file;
            }
        }

        $context->setResponse($this->createFileResponse($files));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['media_upload'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_file_upload';
    }
}
