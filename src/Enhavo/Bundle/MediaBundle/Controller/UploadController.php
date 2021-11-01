<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 21:13
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadController extends AbstractController
{
    use FileControllerTrait;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    public function __construct(
        FileFactory $fileFactory,
        FormatFactory $formatFactory,
        MediaManager $mediaManager)
    {
        $this->fileFactory = $fileFactory;
        $this->formatFactory = $formatFactory;
        $this->mediaManager = $mediaManager;
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
                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);
                    $file->setGarbage(true);
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

    public function replaceAction(Request $request)
    {
        $file = $this->getFileByToken($request);
        $uploadedFile = $this->getUploadedFile($request);

        $newFile = $this->fileFactory->createFromUploadedFile($uploadedFile);

        $file->setContent($newFile->getContent());
        $file->setMimeType($newFile->getMimeType());
        $file->setFilename($newFile->getFilename());
        $file->setExtension($newFile->getExtension());

        $this->mediaManager->saveFile($file);

        return $this->getFileResponse($file);
    }

    public function replaceFormatAction(Request $request)
    {
        $format = $request->get('format');
        $file = $this->getFileByToken($request);
        $uploadedFile = $this->getUploadedFile($request);

        $format = $this->mediaManager->getFormat($file, $format);
        $newFormat = $this->fileFactory->createFromUploadedFile($uploadedFile);

        $format->setContent($newFormat->getContent());
        $format->setMimeType($newFormat->getMimeType());
        $format->setExtension($newFormat->getExtension());

        $this->mediaManager->saveFormat($format);

        return $this->getFileResponse($file);
    }

    /**
     * @param Request $request
     * @return FileInterface
     */
    private function getFileByToken(Request $request)
    {
        $token = $request->get('token');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        if (!$file) {
            throw $this->createNotFoundException();
        }

        return $file;
    }

    private function getUploadedFile(Request $request)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('File not in request');
        }

        if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
            throw new UploadException('Error in file upload');
        }

        return $uploadedFile;
    }
}
