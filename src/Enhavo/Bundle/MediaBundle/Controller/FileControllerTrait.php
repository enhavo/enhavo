<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.10.18
 * Time: 18:41
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

trait FileControllerTrait
{
    private function getFileResponse($files)
    {
        $data = null;

        if(is_array($files)) {
            $data = [];
            /** @var FileInterface $file */
            foreach($files as $file) {
                $data[] = [
                    'id' => $file->getId(),
                    'token' => $file->getToken(),
                    'filename' => $file->getFilename(),
                    'extension' => $file->getExtension(),
                    'mimeType' => $file->getMimeType(),
                    'md5Checksum' => $file->getMd5Checksum()
                ];
            }
        }

        if($files instanceof FileInterface) {
            $data = [
                'id' => $files->getId(),
                'token' => $files->getToken(),
                'filename' => $files->getFilename(),
                'extension' => $files->getExtension(),
                'mimeType' => $files->getMimeType(),
                'md5Checksum' => $files->getMd5Checksum()
            ];
        }

        return new JsonResponse($data);
    }
}