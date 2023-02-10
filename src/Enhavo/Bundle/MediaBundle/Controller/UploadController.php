<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 21:13
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Event\PostUploadEvent;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadController extends AbstractController
{
    use FileControllerTrait;

    public function __construct(
        private FileFactory $fileFactory,
        private FormatFactory $formatFactory,
        private MediaManager $mediaManager,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $eventDispatcher,
        private array $validationGroups,
        private bool $enableGarbageCollection,
    )
    {
    }

    public function uploadAction(Request $request): JsonResponse
    {
        $storedFiles = [];
        foreach($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var $uploadedFile UploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    $errors = $this->getErrors($uploadedFile);
                    if (!count($errors)) {
                        $file = $this->fileFactory->createFromUploadedFile($uploadedFile);
                        if ($this->enableGarbageCollection) {
                            $file->setGarbage(true);
                        } else {
                            $file->setGarbage(false);
                        }
                    }
                    if (count($errors)) {
                        return new JsonResponse([
                            'success' => false,
                            'errors' => $errors,
                        ]);
                    }
                    $this->mediaManager->saveFile($file);
                    $this->eventDispatcher->dispatch(new PostUploadEvent($file), $this->getEventName($request));
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

    protected function getEventName(Request $request)
    {
        return $request->get('event_name') ?: PostUploadEvent::DEFAULT_EVENT_NAME;
    }
}
