<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.10.17
 * Time: 21:53
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Enhavo\Bundle\AppBundle\Behat\Context\KernelAwareContext;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class HttpContext implements KernelAwareContext
{
    use KernelAwareTrait;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Response
     */
    private $request;


    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When request was send
     */
    public function requestWasSend()
    {
        $this->response = $this->kernel->handle($this->request);
    }

    /**
     * @Given parameter :key is :value
     */
    public function parameterIs($key, $value)
    {
        $this->kernel->getContainer()->setParameter($key, $value);
    }

    /**
     * @Given request path is :path
     */
    public function requestPathIs($path)
    {
        $this->request->server->set('REQUEST_URI', $path);
    }

    /**
     * @Given request hostname is :hostname
     */
    public function requestHostNameIs($hostname)
    {
        $this->request->server->set('SERVER_NAME', $hostname);
    }

    /**
     * @Given request post data is:
     */
    public function requestPostDataIs(PyStringNode $string)
    {
        $data = Yaml::parse((string)$string);
        $this->request->setMethod('POST');
        $this->request->request->add($data);
    }

    /**
     * @Then response code is :code
     */
    public function responseCodeIs($code)
    {
        Assert::assertEquals($code, $this->response->getStatusCode());
    }

    /**
     * @Then response body is:
     */
    public function responeBodyIs(PyStringNode $string)
    {
        Assert::assertEquals((string)$string, $this->response->getContent());
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Response
     */
    public function getRequest()
    {
        return $this->request;
    }
}
