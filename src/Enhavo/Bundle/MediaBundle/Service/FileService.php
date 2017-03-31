<?php
/**
 * UploadService.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Service;

use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Enhavo\Bundle\MediaBundle\Entity\File as EnhavoFile;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class FileService
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var FormatManager
     */
    protected $formatManager;

    public function __construct($path, EntityManager $entityManager, FormatManager $formatManager)
    {
        $this->path = $path;
        $this->manager = $entityManager;
        $this->formatManager = $formatManager;

    }

    protected function createPathIfNotExists($path)
    {
        if(!file_exists($path)) {
            if(!mkdir($path, 0755, true)) {
                throw new FileException('Could not create directory: "'.$path.'"');
            }
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     * @return Response
     */
    public function upload(Request $request)
    {
        $files = $request->files->get('files');
        $slugifier = new Slugifier();

        $data = array();

        $error = false;

        foreach($files as $file) {
            /** @var $file UploadedFile */

            if ($file->getError() != UPLOAD_ERR_OK) {
                $error = true;
                continue;
            }

            $filePathinfo = pathinfo($file->getClientOriginalName());
            $slugifiedTitle = $slugifier->slugify($filePathinfo['filename']) . '.' . $filePathinfo['extension'];;

            /** @var $file UploadedFile */
            $entityFile = new EnhavoFile();
            $entityFile->setMimeType($file->getMimeType());
            $entityFile->setExtension($file->guessExtension());
            $entityFile->setSlug($slugifiedTitle);
            $entityFile->setFilename($file->getClientOriginalName());
            $entityFile->setGarbage(true);

            $this->manager->persist($entityFile);
            $this->manager->flush();

            try {
                $this->moveUploadedFile($file, $entityFile);
            } catch(\Exception $exception) {
                $this->manager->remove($entityFile);
                $this->manager->flush();
                throw $exception;
            }

            $data[] = $this->getFileInfo($entityFile);
        }

        if ($error) {
            throw new UploadException('Error in file upload');
        }

        return new JsonResponse($data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function replaceFile($id, Request $request)
    {
        $entityFile = $this->manager->getRepository('EnhavoMediaBundle:File')->find($id);
        if (!$entityFile) {
            throw new NotFoundResourceException;
        }
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if (!$file) {
            throw new \Exception('File not in request');
        }

        $mimeType = $file->getMimeType();
        if ($mimeType != $entityFile->getMimeType()) {
            $entityFile->setMimeType($mimeType);
            $entityFile->setExtension($file->guessExtension());
            $this->manager->persist($entityFile);
            $this->manager->flush();
        }

        $this->moveUploadedFile($file, $entityFile);

        return new JsonResponse(array('files' => array($this->getFileInfo($entityFile))));
    }

    public function getCustomImageSizeResponse(EnhavoFile $file, $width, $height)
    {
        if($width && !$height) {
            $path = $this->path.'/custom/'.$width;
            $this->createPathIfNotExists($path);
            $filepath = $path.'/'.$file->getId();
            if(!file_exists($filepath) || (filemtime($filepath) < filemtime($this->getFilepath($file)))) {
                Resize::make($this->getFilepath($file),$filepath,$width,99999);
            }
        } else {
            $path = $this->path.'/custom/'.$width.'x'.$height;
            $this->createPathIfNotExists($path);
            $filepath = $path.'/'.$file->getId();
            if(!file_exists($filepath) || (filemtime($filepath) < filemtime($this->getFilepath($file)))) {
                Thumbnail::make($this->getFilepath($file),$filepath,$width,$height);
            }
        }

        $response = new BinaryFileResponse($filepath);
        $response->headers->set('Content-Type', $file->getMimeType());
        $filename = $file->getFilename();
        $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
        return $response;
    }

    public function getFileByIdAndName($id, $filename)
    {
        $repository = $this->manager->getRepository('EnhavoMediaBundle:File');
        $file = $repository->findOneBy([
            'id' => $id,
            'filename' => $filename
        ]);
        return $file;
    }

    public function getFileById($id)
    {
        $repository = $this->manager->getRepository('EnhavoMediaBundle:File');
        $file = $repository->find($id);
        return $file;
    }

    public function getResponse(EnhavoFile $file, $forceDownload = false)
    {
        $response = new BinaryFileResponse($this->getFilepath($file));
        if ($forceDownload) {
            $response->headers->set('Content-Type', 'application/octet-stream');
        } else {
            $response->headers->set('Content-Type', $file->getMimeType());
        }
        $filename = $file->getFilename();
        $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
        return $response;
    }

    /**
     * Deletes (unlinks) the file (on the disk) associated with the $file. Also removes generated thumbnails if they exist.
     * Does not remove the database entry.
     *
     * @param EnhavoFile $file
     */
    public function deleteFile(EnhavoFile $file)
    {
        if (file_exists($this->getFilepath($file)))
        {
            unlink($this->getFilepath($file));

            $path = $this->getDirectory($file) . '/custom';
            $thumbDirectories = scandir($path);
            foreach($thumbDirectories as $dir) {
                if ($dir !== '.'
                    && $dir !== '..'
                    && is_dir($path . '/' . $dir)) {
                    if (file_exists($path . '/' . $dir . '/' . $this->getFilename($file))) {
                        unlink($path . '/' . $dir . '/' . $this->getFilename($file));
                    }
                }
            }
        }
    }

    /**
     * Adds an uploaded file to the database. $file can be one of the following:
     * - A string: expected to be absolute path + filename. An Exception will be thrown if the file doesn't exist or is
     *      not readable for the apache user. File extension will be taken from the name, mime type will be determined
     *      by Symfony MimeTypeGuesser.
     * - An instance of SplFileInfo. File extension will be taken from the name, mime type will be determined
     *      by Symfony MimeTypeGuesser.
     * - An instance of Symfony types File or UploadedFile. Attributes will be determined by using the types member
     *      functions.
     *
     * The file will be copied for storage, the original file will not be removed.
     *
     * The attributes mime type, extension, title (slug), file name and order can optionally be configured. If null,
     * they are generated automatically from the file.
     * Note: $title will always be slugified.
     *
     * The optional parameter $garbage sets the initial state of the garbage collector mark (default false). If it
     * remains true, the entry will be deleted by the garbage collector in the future (1 day minimum). This can be
     * useful for forms with ajax upload ahead of the total submit, when an uploaded file is not yet connected to a
     * parent object and the form might be cancelled leaving the file object unconnected and unused.
     *
     * @param string|\SplFileInfo|File|UploadedFile $file           The file to add to the database
     * @param string|null                           $mimeType       [optional] Mime type, overrides automatically
     *                                                              guessed mime type
     * @param string|null                           $fileExtension  [optional] File extension, overrides automatically
     *                                                              determined extension
     * @param string|null                           $slug           [optional] slug, overrides automatically determined
     *                                                              slug (from file name)
     * @param string|null                           $fileName       [optional] File name, overrides automatically
     *                                                              determined file name
     * @param int|null                              $order          [optional] File order index
     * @param bool                                  $garbage        [optional] Initial mark for garbage collector
     * @throws \Exception   Thrown if file does not exist, is not readable, cannot be copied or parameter $file is of
     *                      unknown type.
     * @return EnhavoFile   The generated doctrine entity object (already persisted).
     */
    public function storeFile($file, $mimeType = null, $fileExtension = null, $slug = null, $fileName = null, $order = null, $garbage = false)
    {
        $fileInfo = $this->getFileInformation($file);
        if (!$fileInfo) {
            throw new \InvalidArgumentException("Invalid format on file parameter; possible formats: Symfony\\Component\\HttpFoundation\\File\\UploadedFile, Symfony\\Component\\HttpFoundation\\File\\File, \\SplFileInfo or string (absolute path + filename)");
        }

        $slugifier = new Slugifier;

        if ($mimeType == null) {
            $mimeType = $fileInfo['mime_type'];
        }
        if ($fileExtension == null) {
            $fileExtension = $fileInfo['extension'];
        }
        if ($slug == null) {
            $slug = $fileInfo['filename'];
        }
        $slug = $slugifier->slugify($slug) . '.' . $fileInfo['extension'];
        if ($fileName == null) {
            $fileName = $fileInfo['basename'];
        }

        $entityFile = new EnhavoFile();
        $entityFile->setMimeType($mimeType);
        $entityFile->setExtension($fileExtension);
        $entityFile->setSlug($slug);
        $entityFile->setFilename($fileName);
        $entityFile->setOrder($order);
        $entityFile->setGarbage($garbage);

        $this->manager->persist($entityFile);
        $this->manager->flush();

        try {
            copy($fileInfo['pathname'], $this->getDirectory($entityFile) . '/' . $entityFile->getId());
        } catch(\Exception $exception) {
            $this->manager->remove($entityFile);
            $this->manager->flush();
            throw $exception;
        }

        return $entityFile;
    }

    /**
     * Used by storeFile() to wrap the different possibilities for types of the parameter $file.
     * For a list of possible types, see addFileToDatabase().
     *
     * Returns an array containing:
     *  'pathname'  => The full name of the file (including path)
     *  'extension' => The file extension
     *  'basename'  => The name of the file (with extension, without path)
     *  'filename'  => The name of the file (without path or extension)
     *  'mime_type' => The mime type for the file
     *
     * Returns null if $file is of no known type.
     *
     * @param string|\SplFileInfo|File|UploadedFile $file
     * @return array|null
     * @throws FileException Thrown if $file is a string and the file it points to is not found or not readable for apache user.
     */
    protected function getFileInformation($file)
    {
        if (is_string($file)) {
            if (!is_readable($file)) {
                throw new FileException("File \"$file\" not found or not readable.");
            }
            $fileInfo = pathinfo($file);
            return array(
                'pathname'  => $file,
                'extension' => array_key_exists('extension', $fileInfo) ? $fileInfo['extension'] : null,
                'basename'  => $fileInfo['basename'],
                'filename'  => $fileInfo['filename'],
                'mime_type' => $this->guessMimeType($file)
            );
        }

        if ($file instanceof UploadedFile)
        {
            return array(
                'pathname'  => $file->getPathname(),
                'extension' => $file->guessClientExtension(),
                'basename'  => $file->getClientOriginalName(),
                'filename'  => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'mime_type' => $file->getClientMimeType()
            );

        }
        if ($file instanceof File)
        {
            /** @var $file File */
            return array(
                'pathname'  => $file->getPathname(),
                'extension' => $file->guessExtension(),
                'basename'  => $file->getBasename(),
                'filename'  => $file->getFilename(),
                'mime_type' => $file->getMimeType()
            );
        }
        if ($file instanceof \SplFileInfo)
        {
            /** @var $file SplFileInfo */
            $fileInfo = pathinfo($file->getPathname());
            return array(
                'pathname'  => $file->getPathname(),
                'extension' => $fileInfo['extension'],
                'basename'  => $file->getBasename(),
                'filename'  => $file->getFilename(),
                'mime_type' => $this->guessMimeType($file->getPathname())
            );
        }
        return null;
    }

    /**
     * Uses Symfony MimeTypeGuesser to guess the mime type of a file.
     *
     * @param string $filePathname Full file name including path and extension
     * @return string|null Mime type of null if none found
     */
    protected function guessMimeType($filePathname)
    {
        $guesser = MimeTypeGuesser::getInstance();

        return $guesser->guess($filePathname);
    }

    protected function getDirectory(EnhavoFile $file)
    {
        return $this->path;
    }

    protected function getFilename(EnhavoFile $file)
    {
        return $file->getId();
    }

    public function getFilepath(EnhavoFile $file)
    {
        return $this->getDirectory($file).'/'.$this->getFilename($file);
    }

    protected function moveUploadedFile(UploadedFile $uploadedFile, EnhavoFile $entityFile)
    {
        $targetDir = $this->getDirectory($entityFile);
        $this->createPathIfNotExists($targetDir);
        $uploadedFile->move($targetDir, $this->getFilename($entityFile));
    }

    protected function getFileInfo(EnhavoFile $file)
    {
        $info = array();
        $info['id'] = $file->getId();
        $info['mimeType'] = $file->getMimeType();
        $info['extension'] = $file->getExtension();
        $info['filename'] = $file->getFilename();
        $info['slug'] = $file->getSlug();
        return $info;
    }

    /**
     * @param FileInterface $file
     * @param $format
     *
     * @return File
     */
    public function getFormatFile(FileInterface $file, $format)
    {
        $filePath = sprintf('%s/custom/%s/%s', $this->path, $format, $file->getId());

        if(!file_exists($filePath)) {
            $this->formatManager->createFormat($file, $format);
        }

        return new File($filePath);
    }
}
