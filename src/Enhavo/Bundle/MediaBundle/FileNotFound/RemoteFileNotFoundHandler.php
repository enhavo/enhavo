<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemoteFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    const SERVER_URL_PARAMETER = 'server_url';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private HttpClientInterface $client,
    ) {}

    public function handleFileNotFound(FileInterface $file, array $parameters = []): void
    {
        $url = $this->getRemoteServerUrl($parameters) . $this->urlGenerator->generate($file);

        $response = $this->client->request('GET', $url);
        if($response->getStatusCode() != 200) {
            throw new FileException(sprintf('File not found on remote server: "%s"', $url));
        }
        file_put_contents($file->getContent()->getFilePath(), $response->getContent());
    }

    private function getRemoteServerUrl($parameters)
    {
        if (!isset($parameters[self::SERVER_URL_PARAMETER])) {
            throw new \Exception(sprintf('FileNotFound Strategy of type "%s" requires parameter "%s"', $this->getType(), self::SERVER_URL_PARAMETER));
        }
        return $parameters[self::SERVER_URL_PARAMETER];
    }
}
