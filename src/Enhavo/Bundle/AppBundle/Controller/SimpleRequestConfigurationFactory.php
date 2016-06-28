<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Symfony\Component\HttpFoundation\Request;

class SimpleRequestConfigurationFactory implements SimpleRequestConfigurationFactoryInterface
{
    /**
     * @var ParametersParser
     */
    private $parametersParser;

    /**
     * @var string
     */
    private $configurationClass;

    /**
     * @var array
     */
    private $defaultParameters;

    /**
     * @param ParametersParser $parametersParser
     * @param string $configurationClass
     * @param array $defaultParameters
     */
    public function __construct(ParametersParser $parametersParser, $configurationClass, array $defaultParameters = [])
    {
        $this->parametersParser = $parametersParser;
        $this->configurationClass = $configurationClass;
        $this->defaultParameters = $defaultParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function create(Request $request)
    {
        $parameters = $this->parametersParser->parseRequestValues($this->defaultParameters, $request);
        return new $this->configurationClass($request, new Parameters($parameters));
    }
}
