<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemoteFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    private $parameters = [];
    const SERVER_URL_PARAMETER = 'server_url';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private HttpClientInterface $client,
    ) {}

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        // do nothing
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        $url = $this->getRemoteServerUrl($this->parameters) . $this->urlGenerator->generate($file);

        $response = $this->client->request('GET', $url);
        if($response->getStatusCode() != 200) {
            throw new FileException(sprintf('File not found on remote server: "%s"', $url));
        }
        $content = new Content($response->getContent());
        $file->setContent($content);
        $storage->saveContent($file);
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception): void
    {
        // do nothing
    }

    public function handleFileNotFound(FileInterface|FormatInterface $file, array $parameters = []): void
    {

    }

    private function getRemoteServerUrl($parameters)
    {
        if (!isset($parameters[self::SERVER_URL_PARAMETER])) {
            throw new \Exception(sprintf('FileNotFound Strategy of type "%s" requires parameter "%s"', $this->getType(), self::SERVER_URL_PARAMETER));
        }
        return $parameters[self::SERVER_URL_PARAMETER];
    }
}
