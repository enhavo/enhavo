<?php
namespace Enhavo\Bundle\DownloadBundle\Factory;

use Enhavo\Bundle\DownloadBundle\Entity\Download;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Sylius\Component\Resource\Factory\Factory;

class DownloadFactory extends Factory
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    public function __construct($className, FileFactory $fileFactory)
    {
        parent::__construct($className);

        $this->fileFactory = $fileFactory;
    }

    /**
     * @param Download|null $originalResource
     * @return Download|null
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Download $newDownload */
        $newDownload = $this->createNew();

        $newDownload->setTitle($originalResource->getTitle());
        $newDownload->setText($originalResource->getText());

        $newFile = $this->fileFactory->duplicate($originalResource->getFile());
        $newDownload->setFile($newFile);

        return $newDownload;
    }
}
