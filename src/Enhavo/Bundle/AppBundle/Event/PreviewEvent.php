<?php
/**
 * PreviewEvent.php
 *
 * @since 26/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Event;

use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class PreviewEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var RequestConfigurationInterface
     */
    private $requestConfiguration;

    public function __construct($request, $requestConfiguration)
    {
        $this->request = $request;
        $this->requestConfiguration = $requestConfiguration;
    }

    /**
     * @return RequestConfigurationInterface
     */
    public function getRequestConfiguration()
    {
        return $this->requestConfiguration;
    }

    /**
     * @param RequestConfigurationInterface $requestConfiguration
     */
    public function setRequestConfiguration($requestConfiguration)
    {
        $this->requestConfiguration = $requestConfiguration;
    }

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
}