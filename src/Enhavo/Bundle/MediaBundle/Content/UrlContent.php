<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Content;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UrlContent extends AbstractContent
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $loaded = false;

    public function __construct(
        $url,
        private ?HttpClientInterface $client = null,
    ) {
        $this->url = $url;
        $tempPath = tempnam(sys_get_temp_dir(), 'Content');
        $this->path = $tempPath;

        if (null == $this->client) {
            $this->client = HttpClient::create();
        }
    }

    public function getContent()
    {
        $this->load();

        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        $this->load();

        return $this->path;
    }

    private function load()
    {
        if (true === $this->loaded) {
            return;
        }

        $client = $this->client;
        $response = $client->request('GET', $this->url);
        if (200 != $response->getStatusCode()) {
            throw new FileException(sprintf('File could not be download from uri "%s".', $this->url));
        }

        file_put_contents($this->path, $response->getContent());
    }

    public function __unset($reference)
    {
        if ($this->loaded) {
            unlink($this->path);
        }
    }
}
