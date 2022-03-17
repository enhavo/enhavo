<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\MediaBundle\Controller\FileControllerTrait;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends AbstractController
{
    use FileControllerTrait;

    private MediaLibraryManager $mediaLibraryManager;
    private FileFactory $fileFactory;
    private MediaManager $mediaManager;
    private FormatFactory $formatFactory;

    /**
     * @param MediaLibraryManager $mediaLibraryManager
     * @param FileFactory $fileFactory
     * @param MediaManager $mediaManager
     * @param FormatFactory $formatFactory
     */
    public function __construct(MediaLibraryManager $mediaLibraryManager, FileFactory $fileFactory, MediaManager $mediaManager, FormatFactory $formatFactory)
    {
        $this->mediaLibraryManager = $mediaLibraryManager;
        $this->fileFactory = $fileFactory;
        $this->mediaManager = $mediaManager;
        $this->formatFactory = $formatFactory;
    }


    public function uploadAction(Request $request)
    {
        $storedFiles = [];
        foreach($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    /** @var $uploadedFile UploadedFile */
                    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
                        throw new UploadException('Error in file upload');
                    }
                    /** @var File $file */
                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);
                    $file->setGarbage(true);
                    $file->setContentType($this->mediaLibraryManager->matchContentType($file));
                    $this->mediaManager->saveFile($file);
                    $storedFiles[] = $file;
                } catch(StorageException $exception) {
                    foreach($storedFiles as $file) {
                        $this->mediaManager->deleteFile($file);
                    }
                }
            }
        }

        return $this->getFileResponse($storedFiles);
    }

}
