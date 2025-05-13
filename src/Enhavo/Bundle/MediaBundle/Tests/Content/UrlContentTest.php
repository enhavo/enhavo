<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Tests\Content;

use Enhavo\Bundle\MediaBundle\Content\UrlContent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\ProcessManager\WebServerManager;

class UrlContentTest extends TestCase
{
    /** @var WebServerManager */
    private static $server;

    public static function setUpBeforeClass(): void
    {
        if (null === self::$server) {
            self::$server = new WebServerManager(__DIR__.'/../fixtures/server', '127.0.0.1', 1234, 'index.php', '/ready');
            self::$server->start();
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server instanceof WebServerManager) {
            self::$server->quit();
            self::$server = null;
        }
    }

    private function createDependencies()
    {
        $dependencies = new UrlContentTestDependecies();
        $dependencies->url = 'http://127.0.0.1:1234/content';
        $dependencies->client = new CurlHttpClient();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $instance = new UrlContent(
            $dependencies->url,
            $dependencies->client,
        );

        return $instance;
    }

    public function testGetContent()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('UrlContentTest', $instance->getContent());
    }
}

class UrlContentTestDependecies
{
    /** @var string */
    public $url;

    /** @var ClientInterface|MockObject */
    public $client;
}
