<?php
/**
 * PreviewEvent.php
 *
 * @since 26/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Event;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class PreviewEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var RequestConfiguration
     */
    private $requestConfiguration;

    public function __construct($request, $requestConfiguration)
    {
        $this->request = $request;
        $this->requestConfiguration = $requestConfiguration;
    }

    /**
     * @return RequestConfiguration
     */
    public function getRequestConfiguration()
    {
        return $this->requestConfiguration;
    }

    /**
     * @param RequestConfiguration $requestConfiguration
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
