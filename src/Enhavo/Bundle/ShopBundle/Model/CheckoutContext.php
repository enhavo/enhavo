<?php
/**
 * CheckoutContext.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Model;

use Symfony\Component\HttpFoundation\Request;

class CheckoutContext
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $nextRoute;

    /**
     * @var string
     */
    private $formType;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var ProcessorInterface|null
     */
    private $processor;

    /**
     * @var array
     */
    private $routeParameters = [];

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getNextRoute()
    {
        return $this->nextRoute;
    }

    /**
     * @param string $nextRoute
     */
    public function setNextRoute($nextRoute)
    {
        $this->nextRoute = $nextRoute;
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param string $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return ProcessorInterface|null
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * @param ProcessorInterface|null $processor
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @param array $routeParameters
     */
    public function setRouteParameters($routeParameters)
    {
        $this->routeParameters = $routeParameters;
    }
}