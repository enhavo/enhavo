<?php
namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\DownloadBundle\Factory\DownloadFactory;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Sylius\Component\Resource\Factory\Factory;

class GridFactory extends Factory
{
    /**
     * @var ItemTypeFactory
     */
    protected $itemTypeFactory;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * TODO: Inject by tag or something
     * @var DownloadFactory
     */
    protected $downloadFactory;

    public function __construct($className, ItemTypeFactory $itemTypeFactory, FileFactory $fileFactory, DownloadFactory $downloadFactory)
    {
        parent::__construct($className);

        $this->itemTypeFactory = $itemTypeFactory;
        $this->fileFactory = $fileFactory;
        $this->downloadFactory = $downloadFactory;
    }

    /**
     * @param Grid|null $originalResource
     * @return Grid
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Grid $newGrid */
        $newGrid = $this->createNew();

        //TODO: Containers?

        /** @var Item $item */
        foreach($originalResource->getItems() as $item) {

            $newItem = new Item();
            $newItem->setOrder($item->getOrder());
            $newItem->setConfiguration($item->getConfiguration());
            $newItem->setType($item->getType());

            //TODO: $column?
            //TODO: $itemTypeId?

            $itemType = $item->getItemType();
            $newItemType = $this->itemTypeFactory->create($item->getType());

            //TODO: Use individual factories
            switch($item->getType()) {
                case 'cite_text':
                    $newItemType->setCite($itemType->getCite());
                    break;
                case 'gallery':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setText($itemType->getText());

                    foreach($itemType->getFiles() as $file) {
                        $newFile = $this->fileFactory->duplicate($file);
                        $newItemType->addFile($newFile);
                    }
                    break;
                case 'picture':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setCaption($itemType->getCaption());

                    $newFile = $this->fileFactory->duplicate($itemType->getFile());
                    $newItemType->setFile($newFile);
                    break;
                case 'picture_picture':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setCaptionLeft($itemType->getCaptionLeft());
                    $newItemType->setCaptionRight($itemType->getCaptionRight());

                    $newFile = $this->fileFactory->duplicate($itemType->getFileLeft());
                    $newItemType->setFileLeft($newFile);

                    $newFile = $this->fileFactory->duplicate($itemType->getFileRight());
                    $newItemType->setFileRight($newFile);
                    break;
                case 'text':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setText($itemType->getText());
                    break;
                case 'text_picture':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setText($itemType->getText());
                    $newItemType->setTextLeft($itemType->getTextLeft());
                    $newItemType->setFloat($itemType->getFloat());
                    $newItemType->setCaption($itemType->getCaption());

                    $newFile = $this->fileFactory->duplicate($itemType->getFile());
                    $newItemType->setFile($newFile);
                    break;
                case 'text_text':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setTitleLeft($itemType->getTitleLeft());
                    $newItemType->setTextLeft($itemType->getTextLeft());
                    $newItemType->setTitleRight($itemType->getTitleRight());
                    $newItemType->setTextRight($itemType->getTextRight());
                    $newItemType->setLayout($itemType->getLayout());
                    break;
                case 'three_picture':
                    $newItemType->setTitleLeft($itemType->getTitleLeft());
                    $newItemType->setCaptionLeft($itemType->getCaptionLeft());
                    $newItemType->setTitleCenter($itemType->getTitleCenter());
                    $newItemType->setCaptionCenter($itemType->getCaptionCenter());
                    $newItemType->setTitleRight($itemType->getTitleRight());
                    $newItemType->setCaptionRight($itemType->getCaptionRight());

                    $newFile = $this->fileFactory->duplicate($itemType->getFileLeft());
                    $newItemType->setFileLeft($newFile);

                    $newFile = $this->fileFactory->duplicate($itemType->getFileCenter());
                    $newItemType->setFileCenter($newFile);

                    $newFile = $this->fileFactory->duplicate($itemType->getFileRight());
                    $newItemType->setFileRight($newFile);
                    break;
                case 'video':
                    $newItemType->setTitle($itemType->getTitle());
                    $newItemType->setUrl($itemType->getUrl());
                    break;
                case 'download':
                    // TODO: Handle in DownloadBundle
                    $newItemType->setTitle($itemType->getTitle());

                    $newDownload = $this->downloadFactory->duplicate($itemType->getDownload());
                    $newItemType->setDownload($newDownload);

                    $newFile = $this->fileFactory->duplicate($itemType->getFile());
                    $newItemType->setFile($newFile);
                    break;
            }

            $newItem->setItemType($newItemType);
            $newGrid->addItem($newItem);
        }

        return $newGrid;
    }
}
