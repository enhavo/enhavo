<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Factory;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileFactory extends Factory
{
    use GuessTrait;

    public function __construct(
        string $className,
        private readonly TokenGeneratorInterface $tokenGenerator,
        private readonly ChecksumGeneratorInterface $checksumGenerator,
        private readonly HttpClientInterface $client,
    ) {
        parent::__construct($className);
    }

    public function createNew()
    {
        /** @var FileInterface $file */
        $file = parent::createNew();
        $file->setCreatedAt(new \DateTime());

        return $file;
    }

    public function createTemp(): FileInterface
    {
        $file = $this->createNew();

        $file->setMimeType('application/octet-stream');

        $content = new Content('');
        $file->setContent($content);
        $file->setBasename(basename($content->getFilePath()));

        $this->updateFile($file);

        return $file;
    }

    public function createFromUploadedFile(UploadedFile $uploadedFile): FileInterface
    {
        $file = $this->createNew();

        $file->setMimeType($uploadedFile->getMimeType());
        $file->setBasename($uploadedFile->getClientOriginalName());
        $file->setContent(new PathContent($uploadedFile->getRealPath()));

        $this->updateFile($file);

        return $file;
    }

    public function createFromPath(string $path): FileInterface
    {
        if (!is_readable($path)) {
            throw new FileException(sprintf('File "%s" not found or not readable.', $path));
        }

        $fileInfo = pathinfo($path);

        $file = $this->createNew();

        $file->setMimeType($this->guessMimeType($path));
        $file->setBasename($fileInfo['basename']);
        $file->setContent(new PathContent($path));

        $this->updateFile($file);

        return $file;
    }

    public function createFromContent(mixed $content): FileInterface
    {
        $file = $this->createNew();

        $content = new Content($content);

        $file->setBasename(basename($content->getFilePath()));
        $file->setMimeType($this->guessMimeType($content->getFilePath()));
        $file->setContent($content);

        $this->updateFile($file);

        return $file;
    }

    public function createFromFile(FileInterface $file): FileInterface
    {
        $newFile = $this->createNew();
        $newFile->setContent(new Content($file->getContent()->getContent()));
        $newFile->setMimeType($file->getMimeType());
        $newFile->setFilename($file->getFilename());
        $newFile->setExtension($file->getExtension());
        $newFile->setParameters($file->getParameters());
        $newFile->setOrder($file->getOrder());

        $this->updateFile($newFile);

        return $newFile;
    }

    public function createFromUri(string $uri, ?string $filename = null): FileInterface
    {
        $response = $this->client->request('GET', $uri);
        if (200 != $response->getStatusCode()) {
            throw new FileException(sprintf('File could not be download from uri "%s".', $uri));
        }

        $file = $this->createNew();

        $file->setMimeType('application/octet-stream');
        $headers = $response->getHeaders();
        $contentType = $headers['content-type'];
        if (!empty($contentType)) {
            $parsedHeader = $this->parseHeader($contentType[count($contentType) - 1]);
            if (!empty($parsedHeader) && isset($parsedHeader[0]) && isset($parsedHeader[0][0])) {
                $file->setMimeType($parsedHeader[0][0]);
            }
        }

        if (null === $filename) {
            $contentDisposition = $headers['content-disposition'];
            if (!empty($contentDisposition)) {
                if (preg_match('/.*?filename="(.+?)"/', $contentDisposition[0], $matches)) {
                    $filename = $matches[1];
                }
            } else {
                $path = parse_url($uri, PHP_URL_PATH);
                if (null !== $path) {
                    $basename = pathinfo($path, PATHINFO_BASENAME);
                    if (null !== $basename) {
                        $filename = $basename;
                    }
                }
            }
        }

        if (null === $filename) {
            throw new FileException(sprintf('Can\'t resolve filename from uri "%s".', $uri));
        }

        $file->setBasename($filename);
        $file->setGarbage(true);
        $file->setContent(new Content((string) $response->getContent()));

        $this->updateFile($file);

        return $file;
    }

    private function parseHeader($header): array
    {
        $trimmed = "\"'  \n\t\r";
        $params = $matches = [];

        foreach ($this->normalizeHeader($header) as $val) {
            $part = [];
            foreach (preg_split('/;(?=([^"]*"[^"]*")*[^"]*$)/', $val) as $kvp) {
                if (preg_match_all('/<[^>]+>|[^=]+/', $kvp, $matches)) {
                    $m = $matches[0];
                    if (isset($m[1])) {
                        $part[trim($m[0], $trimmed)] = trim($m[1], $trimmed);
                    } else {
                        $part[] = trim($m[0], $trimmed);
                    }
                }
            }
            if ($part) {
                $params[] = $part;
            }
        }

        return $params;
    }

    private function normalizeHeader($header): array
    {
        if (!is_array($header)) {
            return array_map('trim', explode(',', $header));
        }

        $result = [];
        foreach ($header as $value) {
            foreach ((array) $value as $v) {
                if (false === strpos($v, ',')) {
                    $result[] = $v;
                    continue;
                }
                foreach (preg_split('/,(?=([^"]*"[^"]*")*[^"]*$)/', $v) as $vv) {
                    $result[] = trim($vv);
                }
            }
        }

        return $result;
    }

    protected function updateFile(FileInterface $file): void
    {
        $file->setToken($this->tokenGenerator->generateToken(10));
        $file->setChecksum($this->checksumGenerator->getChecksum($file->getContent()));
    }
}
