<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Endpoint;

use Doctrine\Persistence\ObjectRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;

class MediaLibrarySelectEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly ObjectRepository $itemRepository,
        private readonly ResourceManager $resourceManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $ids = $request->getPayload()->all()['ids'] ?? [];

        $files = [];
        foreach ($ids as $id) {
            $item = $this->itemRepository->find($id);
            if ($item instanceof ItemInterface) {
                /** @var LibraryFileInterface $file */
                $file = $this->fileFactory->createFromFile($item->getFile());
                $file->setGarbage(true);
                $file->setItem($item);
                $this->resourceManager->save($file);
                $files[] = $file;
            }
        }

        $data->set('files', $this->normalize($files, null, ['groups' => 'endpoint']));
    }

    public static function getName(): ?string
    {
        return 'media_library_select';
    }
}
