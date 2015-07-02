<?php
/**
 * UploadService.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\MediaBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use esperanto\MediaBundle\Entity\File;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use esperanto\MediaBundle\Service\Thumbnail;
use esperanto\MediaBundle\Service\Resize;
use BaconStringUtils\Slugifier;

class UploadService
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var EntityManager
     */
    protected $manager;

    public function __construct($path, EntityManager $entityManager)
    {
        $this->path = $path;
        $this->manager = $entityManager;
    }

    protected function createPathIfNotExists($path)
    {
        if(!file_exists($path)) {
            if(!mkdir($path, 0755, true)) {
                throw new Exception('could not create directory: "'.$path.'"');
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

        $data = array();

        foreach($files as $file) {

            $slugifier = new Slugifier;
            $filePathinfo = pathinfo($file->getClientOriginalName());
            $slugifiedTitle = $slugifier->slugify($filePathinfo['filename']);

            /** @var $file UploadedFile */
            $entityFile = new File();
            $entityFile->setMimeType($file->getMimeType());
            $entityFile->setExtension($file->guessExtension());
            $entityFile->setTitle($slugifiedTitle);
            $entityFile->setFilename($file->getClientOriginalName());

            $this->manager->persist($entityFile);
            $this->manager->flush();

            try {
                $this->moveUploadedFile($file, $entityFile);
            } catch(\Exception $exception) {
                $this->manager->remove($entityFile);
                $this->manager->flush();
                throw $exception;
            }

            $data['files'][] = $this->getFileInfo($entityFile);
        }

        return new JsonResponse($data);
    }

    public function getCustomImageSizeResponse($id,$width,$height)
    {
        $repository = $this->manager->getRepository('esperantoMediaBundle:File');
        $file = $repository->find($id);
        if($file === null) {
            throw new NotFoundResourceException;
        }

        if($width && !$height) {
            $path = $this->path.'/custom/'.$width;
            $this->createPathIfNotExists($path);
            $filepath = $path.'/'.$id;
            if(!file_exists($filepath)) {
                Resize::make($this->getFilepath($file),$filepath,$width,99999);
            }
        } else {
            $path = $this->path.'/custom/'.$width.'x'.$height;
            $this->createPathIfNotExists($path);
            $filepath = $path.'/'.$id;
            if(!file_exists($filepath)) {
                Thumbnail::make($this->getFilepath($file),$filepath,$width,$height);
            }
        }

        $response = new BinaryFileResponse($filepath);
        $response->headers->set('Content-Type', $file->getMimeType());
        return $response;
    }

    public function getResponse($id)
    {
        $repository = $this->manager->getRepository('esperantoMediaBundle:File');
        $file = $repository->find($id);
        if($file === null) {
            throw new NotFoundResourceException;
        }

        $response = new BinaryFileResponse($this->getFilepath($file));
        $response->headers->set('Content-Type', $file->getMimeType());
        return $response;
    }

    protected function getDirectory(File $file)
    {
        return $this->path;
    }

    protected function getFilename(File $file)
    {
        return $file->getId();
    }

    protected function getFilepath(File $file)
    {
        return $this->getDirectory($file).'/'.$this->getFilename($file);
    }

    protected function moveUploadedFile(UploadedFile $uploadedFile, File $entityFile)
    {
        $targetDir = $this->getDirectory($entityFile);
        $this->createPathIfNotExists($targetDir);
        $uploadedFile->move($targetDir, $this->getFilename($entityFile));
    }

    protected function getFileInfo(File $file)
    {
        $info = array();
        $info['id'] = $file->getId();
        $info['mimeType'] = $file->getMimeType();
        $info['extension'] = $file->getExtension();
        return $info;
    }
} 
