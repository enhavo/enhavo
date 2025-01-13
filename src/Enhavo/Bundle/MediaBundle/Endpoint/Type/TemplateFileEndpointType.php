<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Symfony\Component\Filesystem\Filesystem;
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
        private readonly FormatFactory $formatFactory,
        private readonly FormatManager $formatManager,
        private readonly Filesystem $fs,
    )
    {
    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        $checksum = $request->get('shortChecksum');
        if (!$checksum) {
            throw new NotFoundHttpException();
        }

        $path = null;
        $formatName = $request->get('format');
        if ($formatName) {
            $path = $this->searchFile($this->dataPath . '/format/' . $formatName, $checksum);
        }

        if ($path === null) {
            $path = $this->searchFile($this->dataPath . '/file', $checksum);

            if ($path && $formatName) {
                $path = $this->generateFormat($path, $formatName, $checksum, $request->get('extension'));
            }
        }

        if ($path === null) {
            throw new NotFoundHttpException();
        }

        $file = $this->fileFactory->createFromPath($path);

        $response = new FileResponse($file);
        return $response;
    }

    private function searchFile(string $path, $checksum): ?string
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
            if ($filename == $checksum) {
                return $file->getRealPath();
            }
        }

        return null;
    }

    private function generateFormat($path, $formatName, $checksum, $extension = null): string
    {
        $file = $this->fileFactory->createFromPath($path);
        $format = $this->formatFactory->createFromMediaFile($formatName, $file);

        $format = $this->formatManager->applyFilter($format);

        $targetPath = sprintf('%s/format/%s/%s', $this->dataPath, $formatName, $checksum);
        if ($extension) {
            $targetPath .= '.' . $extension;
        }

        $this->fs->dumpFile($targetPath, $format->getContent()->getContent());

        return $targetPath;
    }

    public static function getName(): ?string
    {
        return 'template_file';
    }
}
