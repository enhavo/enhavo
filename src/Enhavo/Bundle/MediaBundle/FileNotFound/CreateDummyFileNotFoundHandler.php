<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

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
    public const PARAMETER_SAVE_TO_DISK = 'save_to_disk';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $savePath,
    ) {
    }

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        $content = $this->loadDummyIfExists($file, $parameters);
        if (!$content) {
            if ($this->isImage($file)) {
                $content = $this->createRandomImage($file, $parameters);
            } elseif ($this->isPdf($file)) {
                $content = $this->createPdf($file, $parameters);
            } else {
                $content = $this->createBlankFile($file, $parameters);
            }
        }

        $file->setContent($content);
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    private function createRandomImage(FileInterface|FormatInterface $file, array $parameters): ContentInterface
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

    private function isImage(FileInterface|FormatInterface $file): bool
    {
        return 'image' == strtolower(substr($file->getMimeType(), 0, 5));
    }

    private function isPdf(FileInterface|FormatInterface $file): bool
    {
        return 'application/pdf' === $file->getMimeType();
    }

    private function createPdf(FileInterface|FormatInterface $file, array $parameters)
    {
        $path = $this->getSavePath($file->getChecksum(), $parameters);

        // Empty PDF
        $this->filesystem->dumpFile($path, base64_decode(
            'JVBERi0xLjUKJcOkw7zDtsOfCjIgMCBvYmoKPDwvTGVuZ3RoIDMgMCBSL0ZpbHRlci9GbGF0ZURl
            Y29kZT4+CnN0cmVhbQp4nDPQM1Qo5ypUMFAw0DMwslAwtTTVMzI3VbAwMdSzMDNUKErlCtdSyOMK
            VAAAtxIIrgplbmRzdHJlYW0KZW5kb2JqCgozIDAgb2JqCjUwCmVuZG9iagoKNSAwIG9iago8PAo+
            PgplbmRvYmoKCjYgMCBvYmoKPDwvRm9udCA1IDAgUgovUHJvY1NldFsvUERGL1RleHRdCj4+CmVu
            ZG9iagoKMSAwIG9iago8PC9UeXBlL1BhZ2UvUGFyZW50IDQgMCBSL1Jlc291cmNlcyA2IDAgUi9N
            ZWRpYUJveFswIDAgNTk1LjMwMzkzNzAwNzg3NCA4NDEuODg5NzYzNzc5NTI4XS9Hcm91cDw8L1Mv
            VHJhbnNwYXJlbmN5L0NTL0RldmljZVJHQi9JIHRydWU+Pi9Db250ZW50cyAyIDAgUj4+CmVuZG9i
            agoKNCAwIG9iago8PC9UeXBlL1BhZ2VzCi9SZXNvdXJjZXMgNiAwIFIKL01lZGlhQm94WyAwIDAg
            NTk1IDg0MSBdCi9LaWRzWyAxIDAgUiBdCi9Db3VudCAxPj4KZW5kb2JqCgo3IDAgb2JqCjw8L1R5
            cGUvQ2F0YWxvZy9QYWdlcyA0IDAgUgovT3BlbkFjdGlvblsxIDAgUiAvWFlaIG51bGwgbnVsbCAw
            XQovTGFuZyhkZS1ERSkKPj4KZW5kb2JqCgo4IDAgb2JqCjw8L0NyZWF0b3I8RkVGRjAwNTcwMDcy
            MDA2OTAwNzQwMDY1MDA3Mj4KL1Byb2R1Y2VyPEZFRkYwMDRDMDA2OTAwNjIwMDcyMDA2NTAwNEYw
            MDY2MDA2NjAwNjkwMDYzMDA2NTAwMjAwMDM2MDAyRTAwMzQ+Ci9DcmVhdGlvbkRhdGUoRDoyMDI1
            MDExNTE1MzYyOSswMScwMCcpPj4KZW5kb2JqCgp4cmVmCjAgOQowMDAwMDAwMDAwIDY1NTM1IGYg
            CjAwMDAwMDAyMzQgMDAwMDAgbiAKMDAwMDAwMDAxOSAwMDAwMCBuIAowMDAwMDAwMTQwIDAwMDAw
            IG4gCjAwMDAwMDA0MDIgMDAwMDAgbiAKMDAwMDAwMDE1OSAwMDAwMCBuIAowMDAwMDAwMTgxIDAw
            MDAwIG4gCjAwMDAwMDA1MDAgMDAwMDAgbiAKMDAwMDAwMDU5NiAwMDAwMCBuIAp0cmFpbGVyCjw8
            L1NpemUgOS9Sb290IDcgMCBSCi9JbmZvIDggMCBSCi9JRCBbIDwwNjY5QUEwM0M1ODExMTNCQ0ND
            OEEzNzBCNEJFNkQ4ND4KPDA2NjlBQTAzQzU4MTExM0JDQ0M4QTM3MEI0QkU2RDg0PiBdCi9Eb2ND
            aGVja3N1bSAvNTg3QjE1MkEzNEI1MjZCRkM5MzIyMTM2NjMxMDNCQTAKPj4Kc3RhcnR4cmVmCjc3
            MAolJUVPRgo='
        ));

        return new PathContent($path);
    }

    private function createBlankFile(FileInterface|FormatInterface $file, array $parameters): ContentInterface
    {
        $path = $this->getSavePath($file->getChecksum(), $parameters);
        $this->filesystem->dumpFile($path, '');

        return new PathContent($path);
    }

    private function loadDummyIfExists(FileInterface|FormatInterface $file, array $parameters): ?ContentInterface
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

        return $this->savePath.'/'.substr($checksum, 0, 2).'/'.substr($checksum, 2);
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
                list($red, $green, $blue) = [$valueNormalized, $v3, $v1];
                break;
            case 1:
                list($red, $green, $blue) = [$v2, $valueNormalized, $v1];
                break;
            case 2:
                list($red, $green, $blue) = [$v1, $valueNormalized, $v3];
                break;
            case 3:
                list($red, $green, $blue) = [$v1, $v2, $valueNormalized];
                break;
            case 4:
                list($red, $green, $blue) = [$v3, $v1, $valueNormalized];
                break;
            case 5:
            case 6: // for when $H=1 is given
                list($red, $green, $blue) = [$valueNormalized, $v1, $v2];
                break;
        }

        return '#'.dechex(floor($red * 255)).dechex(floor($green * 255)).dechex(floor($blue * 255));
    }
}
