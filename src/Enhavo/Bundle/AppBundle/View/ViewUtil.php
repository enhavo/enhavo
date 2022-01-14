<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.12.18
 * Time: 22:04
 */

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Util\ArrayUtil;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration as SyliusRequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ViewUtil
{
    /** @var array */
    private $defaultParameters;

    /** @var ParametersParserInterface */
    private $parametersParser;

    /** @var string */
    private $configurationClass;

    /** @var RouterInterface */
    private $router;

    /** @var RequestConfigurationFactory */
    private $requestConfigurationFactory;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var RequestStack */
    private $requestStack;

    /**
     * ViewerUtil constructor.
     *
     * @param ParametersParserInterface $parametersParser
     * @param $configurationClass
     * @param array $defaultParameters
     * @param RouterInterface $router
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RequestStack $requestStack
     */
    public function __construct(
        ParametersParserInterface $parametersParser,
        string $configurationClass,
        array $defaultParameters,
        RouterInterface $router,
        RequestConfigurationFactory $requestConfigurationFactory,
        AuthorizationCheckerInterface $authorizationChecker,
        RequestStack $requestStack
    ) {
        $this->defaultParameters = $defaultParameters;
        $this->parametersParser = $parametersParser;
        $this->configurationClass = $configurationClass;
        $this->router = $router;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
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

    public function getRequestConfiguration(array $options): ?RequestConfiguration
    {
        $requestConfiguration = $options['request_configuration'];
        if($requestConfiguration instanceof RequestConfiguration) {
            return $requestConfiguration;
        } else {
            /** @var Request $request */
            $request = $options['request'];
            $metadata = new DummyMetadata();
            /** @var RequestConfiguration $requestConfiguration */
            $requestConfiguration = $this->requestConfigurationFactory->create($metadata, $request);
            return $requestConfiguration;
        }
    }

    public function getUnderscoreName(MetadataInterface $metadata)
    {
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        return $name;
    }


    public function mergeConfigArray($configs)
    {
        $data = [];
        foreach($configs as $config) {
            if(is_array($config)) {
                $data = ArrayUtil::merge($data, $config);
            }
        }
        return $data;
    }

    public function mergeConfig($configs)
    {
        $data = null;
        foreach($configs as $config) {
            if($config != null) {
                $data = $config;
            }
        }
        return $data;
    }

    public function getViewerOption($key, RequestConfiguration $requestConfiguration)
    {
        $options = $requestConfiguration->getViewerOptions();
        return $this->getConfigValue($key, $options);
    }

    /**
     * @param $bundlePrefix
     * @param $resourceName
     * @param $action
     * @return string
     */
    public function getRoleNameByResourceName($bundlePrefix, $resourceName, $action)
    {
        $roleName = strtoupper(sprintf('ROLE_%s_%s_%s', $bundlePrefix, $resourceName, $action));
        return $roleName;
    }

    /**
     * @param SyliusRequestConfiguration $configuration
     * @param string $permission
     *
     * @throws AccessDeniedException
     */
    public function isGrantedOr403(SyliusRequestConfiguration $configuration, string $permission): void
    {
        if (!$configuration->hasPermission()) {
            $permission = $this->getRoleName($permission, $configuration->getMetadata());
            if (!$this->authorizationChecker->isGranted($configuration, $permission)) {
                throw new AccessDeniedException();
            }
            return;
        }

        $permission = $configuration->getPermission($permission);
        if (!$this->authorizationChecker->isGranted($configuration, $permission)) {
            throw new AccessDeniedException();
        }

        return;
    }

    public function getRoleName(string $permission, $metadata): string
    {
        $name = $metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        $role = sprintf(
            'role_%s_%s_%s',
            $metadata->getApplicationName(),
            $name,
            $permission
        );
        return strtoupper($role);
    }

    public function updateRequest()
    {
        $request = $this->requestStack->getMainRequest();

        if ($request->getSession() && $request->getSession()->has('enhavo.post')) {
            $postData = $request->getSession()->get('enhavo.post');
            if($postData) {
                foreach($postData as $key => $value) {
                    $request->query->set($key, $value);
                }
                $request->getSession()->remove('enhavo.post');
            }
        }
    }
}
