<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-08
 * Time: 21:27
 */

namespace Enhavo\Bundle\MediaBundle\Cache;


use Enhavo\Bundle\MediaBundle\Media\UrlResolver;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class HttpCache implements CacheInterface
{
    /**
     * @var UrlResolver;
     */
    private $resolver;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $schema;

    public function __construct(RequestStack $requestStack, $host = null, $port = null, $schema = null, $timeout = 2)
    {
        $this->requestStack = $requestStack;

            $this->host = $host;
            $this->port = $port;
            $this->schema = $schema;

        $this->client = new Client([
            'timeout' => $timeout
        ]);
    }

    private function getUri(FileInterface $file, $format)
    {
        $path = $this->resolver->getPublicFormatUrl($file, $format);
        $request = $this->requestStack->getMasterRequest();

        $host = $this->host ? $this->host :$request->getHost();
        $port = $this->port ? $this->port :$request->getPort();
        $schema = $this->schema ? $this->schema :$request->getScheme();

        return sprintf('%s://%s:%s%s', $schema, $host, $port, $path);
    }

    public function setResolver(UrlResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function invalid(FileInterface $file, $format)
    {
        $uri = $this->getUri($file, $format);
        $request = new Request('PURGE', $uri);
        $this->client->send($request);
    }

    public function set(FileInterface $file, $format)
    {

    }

    public function refresh(FileInterface $file, $format)
    {
        $this->invalid($file, $format);
    }
}
