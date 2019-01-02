<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.12.18
 * Time: 22:04
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ViewerUtil
{
    /**
     * @var array
     */
    private $defaultParameters;

    /**
     * @var ParametersParserInterface
     */
    private $parametersParser;

    /**
     * @var string
     */
    private $configurationClass;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ViewerUtil constructor.
     *
     * @param ParametersParserInterface $parametersParser
     * @param $configurationClass
     * @param array $defaultParameters
     * @param RouterInterface $router
     */
    public function __construct(ParametersParserInterface $parametersParser, string $configurationClass, array $defaultParameters, RouterInterface $router)
    {
        $this->defaultParameters = $defaultParameters;
        $this->parametersParser = $parametersParser;
        $this->configurationClass = $configurationClass;
        $this->router = $router;
    }

    /**
     * @param $route
     * @return RequestConfiguration
     */
    public function createConfigurationFromRoute($route)
    {
        $route = $this->router->getRouteCollection()->get($route);
        if($route === null) {
            return null;
        }

        $parameters = $route->getDefault('_sylius');
        $request = new Request();
        $metadata = new DummyMetadata();
        $parameters = array_merge($this->defaultParameters, $parameters);
        $parameters = $this->parametersParser->parseRequestValues($parameters, $request);

        return new $this->configurationClass($metadata, $request, new Parameters($parameters));
    }

    public function getConfigValue($key, $config)
    {
        $keyArray = preg_split('/\./', $key);
        $value = $this->getByKeyArray($config, $keyArray);
        return $value;
    }

    private function getByKeyArray($config, $keyArray)
    {
        if(empty($keyArray)) {
            return null;
        }

        if(is_array($keyArray)) {
            $key = array_shift($keyArray);
            if(isset($config[$key])) {
                if(count($keyArray) == 0) {
                    return $config[$key];
                } else {
                    return $this->getByKeyArray($config[$key], $keyArray);
                }
            }
        }

        return null;
    }
}