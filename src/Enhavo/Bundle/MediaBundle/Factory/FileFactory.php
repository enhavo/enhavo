<?php
namespace Enhavo\Bundle\MediaBundle\Factory;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileFactory extends Factory
{
    use GuessTrait;

    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct($className, ProviderInterface $provider, private HttpClientInterface $client)
    {
        parent::__construct($className);
        $this->provider = $provider;
    }

    public function createNew()
    {
        /** @var FileInterface $file */
        $file = parent::createNew();
        $file->setCreatedAt(new \DateTime());

        return $file;
    }

    public function createTemp()
    {
        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType('application/octet-stream');
        $file->setExtension(null);

        $content = new Content('');
        $file->setContent($content);
        $file->setFilename(basename($content->getFilePath()));

        $this->provider->updateFile($file);
        return $file;
    }

    /**
     * @param FileInterface $originalResource
     * @return FileInterface
     */
    public function duplicate(FileInterface $originalResource)
    {
        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType($originalResource->getMimeType());
        $file->setExtension($originalResource->getExtension());
        $file->setOrder($originalResource->getOrder());
        $file->setFilename($originalResource->getFilename());
        $file->setParameters($originalResource->getParameters());
        $file->setLibrary($originalResource->isLibrary());

        $content = new Content($originalResource->getContent()->getContent());
        $file->setContent($content);

        $this->provider->updateFile($file);
        return $file;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return FileInterface
     */
    public function createFromUploadedFile(UploadedFile $uploadedFile)
    {
        /** @var File $newFile */
        $file = $this->createNew();

        $file->setMimeType($uploadedFile->getMimeType());
        $file->setExtension($uploadedFile->guessClientExtension());
        $file->setFilename($uploadedFile->getClientOriginalName());
        $file->setContent(new PathContent($uploadedFile->getRealPath()));

        $this->provider->updateFile($file);
        return $file;
    }

    /**
     * @param File $file
     * @return FileInterface
     */
    public function createFromFile(File $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param SplFileInfo $file
     * @return FileInterface
     */
    public function createFromSplFileInfo(SplFileInfo $file)
    {
        return $this->createFromPath($file->getRealPath());
    }

    /**
     * @param string $path
     * @return FileInterface
     * @throws FileException
     */
    public function createFromPath($path)
    {
        if (!is_readable($path)) {
            throw new FileException(sprintf('File "%s" not found or not readable.', $path));
        }

        $fileInfo = pathinfo($path);

        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType($this->guessMimeType($path));
        $file->setExtension(array_key_exists('extension', $fileInfo) ? $fileInfo['extension'] : $this->guessExtension($path));
        $file->setFilename($fileInfo['basename']);
        $file->setContent(new PathContent($path));

        $this->provider->updateFile($file);
        return $file;
    }

    public function createFromContent($content)
    {
        /** @var FileInterface $file */
        $file = $this->createNew();

        $content = new Content($content);

        $file->setFilename(basename($content->getFilePath()));
        $file->setExtension($this->guessExtension($content->getFilePath()));
        $file->setMimeType($this->guessMimeType($content->getFilePath()));
        $file->setContent($content);

        $this->provider->updateFile($file);
        return $file;
    }

    /**
     * @param string $uri
     * @param string $filename
     * @return FileInterface
     */
    public function createFromUri($uri, $filename = null)
    {
        $response = $this->client->request('GET', $uri);
        if($response->getStatusCode() != 200) {
            throw new FileException(sprintf('File could not be download from uri "%s".', $uri));
        }

        /** @var FileInterface $file */
        $file = $this->createNew();

        $file->setMimeType('application/octet-stream');
        $headers = $response->getHeaders();
        $contentType = $headers['content-type'];
        if(!empty($contentType)) {
            $parsedHeader = $this->parse($contentType[count($contentType)-1]);
            if(!empty($parsedHeader) && isset($parsedHeader[0]) && isset($parsedHeader[0][0])) {
                $file->setMimeType($parsedHeader[0][0]);
            }
        }

        if($filename === null) {
            $contentDisposition = $headers['content-disposition'];
            if(!empty($contentDisposition)) {
                if (preg_match('/.*?filename="(.+?)"/', $contentDisposition[0], $matches)) {
                    $filename = $matches[1];
                }
            } else {
                $path = parse_url($uri, PHP_URL_PATH);
                if($path !== null) {
                    $basename = pathinfo($path, PATHINFO_BASENAME);
                    if($basename !== null) {
                        $filename = $basename;
                    }
                }
            }
        }

        if($filename === null) {
            throw new FileException(sprintf('Can\'t resolve filename from uri "%s".', $uri));
        }

        $file->setFilename($filename);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file->setExtension($extension);

        $file->setGarbage(true);
        $file->setContent(new Content((string)$response->getContent()));

        $this->provider->updateFile($file);
        return $file;
    }

    private function parse($header)
    {
        $trimmed = "\"'  \n\t\r";
        $params = $matches = [];

        foreach ($this->normalize($header) as $val) {
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

    private function normalize($header)
    {
        if (!is_array($header)) {
            return array_map('trim', explode(',', $header));
        }

        $result = [];
        foreach ($header as $value) {
            foreach ((array) $value as $v) {
                if (strpos($v, ',') === false) {
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
}
