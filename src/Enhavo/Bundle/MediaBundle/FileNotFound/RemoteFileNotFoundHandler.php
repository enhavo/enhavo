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

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemoteFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    public const PARAMETER_SERVER_URL = 'server_url';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private HttpClientInterface $client,
    ) {
    }

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        $url = $this->getRemoteServerUrl($parameters).$this->urlGenerator->generate($file);

        $response = $this->client->request('GET', $url);
        if (200 != $response->getStatusCode()) {
            throw new FileException(sprintf('File not found on remote server: "%s"', $url));
        }
        $content = new Content($response->getContent());
        $file->setContent($content);
        $storage->saveContent($file);
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        // do nothing
    }

    public function handleFileNotFound(FileInterface|FormatInterface $file, array $parameters = []): void
    {
    }

    private function getRemoteServerUrl($parameters)
    {
        if (!isset($parameters[self::PARAMETER_SERVER_URL])) {
            throw new \Exception(sprintf('FileNotFoundHandler of type "%s" requires parameter "%s"', self::class, self::PARAMETER_SERVER_URL));
        }

        return $parameters[self::PARAMETER_SERVER_URL];
    }
}
