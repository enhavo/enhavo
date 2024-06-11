<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-08
 * Time: 21:27
 */

namespace Enhavo\Bundle\MediaBundle\Cache;

use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HttpCache implements CacheInterface
{
    /**
     * @var UrlGeneratorInterface;
     */
    private $generator;

    public function __construct(
        private RequestStack $requestStack,
        private HttpClientInterface $client,
        private $host = null,
        private $port = null,
        private $schema = null,
        private $timeout = 2,
        private $method = 'PURGE'
    )
    {}

    private function getUri(FileInterface $file, $format)
    {
        $path = $this->generator->generateFormat($file, $format);
        $request = $this->requestStack->getMasterRequest();

        $host = $this->host ? $this->host :$request->getHost();
        $port = $this->port ? $this->port :$request->getPort();
        $schema = $this->schema ? $this->schema :$request->getScheme();

        return sprintf('%s://%s:%s%s', $schema, $host, $port, $path);
    }

    public function setUrlGenerator(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function invalid(FileInterface $file, $format)
    {
        $uri = $this->getUri($file, $format);
        $this->client->request($this->method, $uri, ['timeout' => $this->timeout]);
    }

    public function set(FileInterface $file, $format)
    {

    }

    public function refresh(FileInterface $file, $format)
    {
        $this->invalid($file, $format);
    }
}
