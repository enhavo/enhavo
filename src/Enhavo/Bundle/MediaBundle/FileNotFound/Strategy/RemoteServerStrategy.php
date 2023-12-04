<?php

namespace Enhavo\Bundle\MediaBundle\FileNotFound\Strategy;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\FileNotFound\FileNotFoundStrategyInterface;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use GuzzleHttp\Client;

class RemoteServerStrategy implements FileNotFoundStrategyInterface
{
    const SERVER_URL_PARAMETER = 'server_url';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function handleFileNotFound(FileInterface $file, ?array $strategyParameters): void
    {
        $url = $this->getRemoteServerUrl($strategyParameters) . $this->urlGenerator->generate($file);

        $client = new Client();
        $response = $client->request('GET', $url);
        if($response->getStatusCode() != 200) {
            throw new FileException(sprintf('File not found on remote server: "%s"', $url));
        }
        file_put_contents($file->getContent()->getFilePath(), $response->getBody());
    }

    private function getRemoteServerUrl($parameters)
    {
        if (!isset($parameters[self::SERVER_URL_PARAMETER])) {
            throw new \Exception(sprintf('FileNotFound Strategy of type "%s" requires parameter "%s"', $this->getType(), self::SERVER_URL_PARAMETER));
        }
        return $parameters[self::SERVER_URL_PARAMETER];
    }

    public function getType()
    {
        return 'remote_server';
    }
}
