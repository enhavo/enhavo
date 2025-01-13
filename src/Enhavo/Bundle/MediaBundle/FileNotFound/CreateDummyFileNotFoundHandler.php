<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Fill\Gradient\Vertical;
use Imagine\Image\Palette\RGB as RGBPalette;
use Imagine\Image\Point;
use Symfony\Component\Filesystem\Filesystem;

class CreateDummyFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    const PARAMETER_SAVE_TO_DISK = 'save_to_disk';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $savePath,
    ) {}

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        $originalFile = $file;
        if ($file instanceof FormatInterface) {
            $file = $file->getFile();
        }

        $content = $this->loadDummyIfExists($file, $parameters);
        if (!$content) {
            if ($file->isImage()) {
                $content = $this->createRandomImage($file, $parameters);
            } else {
                $content = $this->createBlankFile($file, $parameters);
            }
        }

        $originalFile->setContent($content);
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    private function createRandomImage(FileInterface $file, array $parameters): ContentInterface
    {
        $imagine = new Imagine();

        $colors = $this->generateColors($file->getId());
        $palette = new RGBPalette();

        $image = $imagine->create(new Box(500, 500));
        $image->fill(new Vertical(
            500,
            $palette->color($colors[0]),
            $palette->color($colors[1])
        ));
        $box = $imagine->create(new Box(200, 200));
        $box->fill(new Vertical(
            500,
            $palette->color($colors[3]),
            $palette->color($colors[2])
        ));

        $image->paste($box, new Point(150, 150));

        $path = $this->getSavePath($file->getChecksum(), $parameters);
        $this->filesystem->dumpFile($path, '');
        $image->save($path, [
            'format' => 'jpg',
        ]);
        return new PathContent($path);
    }

    private function createBlankFile(FileInterface $file, array $parameters): ContentInterface
    {
        $path = $this->getSavePath($file->getChecksum(), $parameters);
        $this->filesystem->dumpFile($path, '');
        return new PathContent($path);
    }

    private function loadDummyIfExists(FileInterface $file, array $parameters): ?ContentInterface
    {
        if (!$this->saveToDisk($parameters)) {
            return null;
        }

        $path = $this->getSavePath($file->getChecksum(), $parameters);
        if ($this->filesystem->exists($path)) {
            return new PathContent($path);
        }
        return null;
    }

    private function getSavePath($checksum, array $parameters): string
    {
        if (!$this->saveToDisk($parameters)) {
            return tempnam(sys_get_temp_dir(), 'Content');
        }
        return $this->savePath . '/' . substr($checksum, 0, 2) . '/' . substr($checksum, 2);
    }

    private function saveToDisk(array $parameters): bool
    {
        if (array_key_exists(self::PARAMETER_SAVE_TO_DISK, $parameters)) {
            return $parameters[self::PARAMETER_SAVE_TO_DISK];
        }
        return true;
    }

    private function generateColors($randomSeed): array
    {
        $hue = (intval($randomSeed) * 100) % 256;
        return [
            0 => $this->hvsToRgbColorCode($hue, 24, 255),
            1 => $this->hvsToRgbColorCode($hue, 48, 255),
            2 => $this->hvsToRgbColorCode($hue, 64, 255),
            3 => $this->hvsToRgbColorCode($hue, 128, 255),
        ];
    }

    private function hvsToRgbColorCode(int $hue, int $saturation, int $value): string
    {
        // Convert from 0..255 for 0..1
        $hueNormalized = $hue / 255.0;
        $saturationNormalized = $saturation / 255.0;
        $valueNormalized = $value / 255.0;

        $hueNormalized *= 6;
        $hueInt = floor($hueNormalized);
        $hueDecimal = $hueNormalized - $hueInt;

        $v1 = $valueNormalized * (1 - $saturationNormalized);
        $v2 = $valueNormalized * (1 - $saturationNormalized * $hueDecimal);
        $v3 = $valueNormalized * (1 - $saturationNormalized * (1 - $hueDecimal));

        $red = 0;
        $green = 0;
        $blue = 0;

        switch ($hueInt) {
            case 0:
                list($red, $green, $blue) = array($valueNormalized, $v3, $v1);
                break;
            case 1:
                list($red, $green, $blue) = array($v2, $valueNormalized, $v1);
                break;
            case 2:
                list($red, $green, $blue) = array($v1, $valueNormalized, $v3);
                break;
            case 3:
                list($red, $green, $blue) = array($v1, $v2, $valueNormalized);
                break;
            case 4:
                list($red, $green, $blue) = array($v3, $v1, $valueNormalized);
                break;
            case 5:
            case 6: //for when $H=1 is given
                list($red, $green, $blue) = array($valueNormalized, $v1, $v2);
                break;
        }

        return '#' . dechex(floor($red * 255)) . dechex(floor($green * 255)) . dechex(floor($blue * 255));
    }
}
