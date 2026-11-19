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
use Enhavo\Bundle\MediaBundle\Endpoint\Type\FileUploadEndpointType;
use Enhavo\Bundle\MediaLibraryBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Upload of an admin media field that also lands in the media library:
 * The parent stores the file, this type adds a copy of it to the library as a new item and links to that item.
 */
class MediaLibraryFileUploadEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly ItemRepository $itemRepository,
        private readonly MediaLibraryManager $mediaLibraryManager,
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        foreach ($context->get('files', []) as $file) {
            $item = $this->itemRepository->findByChecksum($file->getChecksum())[0] ?? null;
            if (!$item instanceof ItemInterface) {
                $item = $this->mediaLibraryManager->createItem($this->fileFactory->createFromFile($file));
                $this->resourceManager->save($item);
            }

            if ($file instanceof LibraryFileInterface) {
                $file->setItem($item);
                $this->resourceManager->save($file);
            }
        }
    }

    public static function getName(): ?string
    {
        return 'media_library_file_upload';
    }

    public static function getParentType(): ?string
    {
        return FileUploadEndpointType::class;
    }
}
