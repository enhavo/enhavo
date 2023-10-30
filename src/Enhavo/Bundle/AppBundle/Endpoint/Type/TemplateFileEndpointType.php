<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Http\FileResponse;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TemplateFileEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly string $dataPath,
        private readonly FileFactory $fileFactory,
        private readonly Filesystem $fs,
    )
    {
    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        $id = $request->get('id');
        if (!$id) {
            throw new NotFoundHttpException();
        }

        $path = null;
        $format = $request->get('format');
        if ($format) {
            $path = $this->searchFile($this->dataPath . '/format/' . $format, $id);
        }

        if ($path === null) {
            $path = $this->searchFile($this->dataPath . '/file', $id);
        }

        if ($path === null) {
            throw new NotFoundHttpException();
        }

        $file = $this->fileFactory->createFromPath($path);

        $response = new FileResponse($file);
        return $response;
    }

    private function searchFile(string $path, $id): ?string
    {
        if (!$this->fs->exists($path)) {
            return null;
        }

        $finder = new Finder();
        $finder->files()->in($path);

        if (!$finder->hasResults()) {
            return null;
        }

        foreach ($finder as $file) {
            $filename = pathinfo($file->getRealPath(), PATHINFO_FILENAME);
            if ($filename == $id) {
                return $file->getRealPath();
            }
        }

        return null;
    }

    public static function getName(): ?string
    {
        return 'template_file';
    }
}
