<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.07.18
 * Time: 23:51
 */

namespace Bundle\MultiTenancyBundle\Resolver;

use Bundle\MultiTenancyBundle\Exception\MultiTenancyResolverException;
use Bundle\MultiTenancyBundle\Manager\MultiTenancyManager;
use Bundle\MultiTenancyBundle\Model\MultiTenancyConfiguration;
use Symfony\Component\HttpFoundation\Request;

class MultiTenancyResolver
{
    /**
     * @var MultiTenancyConfiguration
     */
    private $configuration;

    /**
     * @var MultiTenancyManager
     */
    private $multiTenancyManager;

    /**
     * MultiTenancyResolver constructor.
     * @param MultiTenancyManager $multiTenancyManager
     */
    public function __construct(MultiTenancyManager $multiTenancyManager)
    {
        $this->multiTenancyManager = $multiTenancyManager;
    }

    public function getKey()
    {
        return $this->configuration->getKey();
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function resolve(Request $request)
    {
        if($this->isResolved()) {
            return;
        }

        $configurations = $this->multiTenancyManager->getConfigurations();
        $multiTenancyKeys = array_keys($configurations);

        // try env
        $multiTenancyEnv = getenv('PORTAL');
        if($multiTenancyEnv && !in_array($multiTenancyEnv, $multiTenancyKeys)) {
            throw new MultiTenancyResolverException(sprintf(
                'Environment variable was defined as "%s", but does not match any of this multiTenancys [%s]',
                $multiTenancyEnv,
                implode(',', $multiTenancyKeys)
            ));
        }

        if($multiTenancyEnv) {
            $this->configuration = $configurations[$multiTenancyEnv];
            return;
        }

        // try host
        $host = $request->getHost();
        /** @var MultiTenancyConfiguration $configuration */
        foreach($configurations as $configuration) {
            foreach($configuration->getDomains() as $domain) {
                if($domain == $host) {
                    $this->configuration = $configuration;
                    return;
                }
            }
        }

        throw new MultiTenancyResolverException(sprintf(
            'Can\'t resolve multiTenancy. No environment variable was set and can\'t find host "%s" in any of this multiTenancys [%s]',
            $host,
            implode(',', $multiTenancyKeys)
        ));
    }

    public function isResolved()
    {
        return $this->configuration !== null;
    }

    public function setMultiTenancy($key)
    {
        $this->configuration = $this->multiTenancyManager->getConfiguration($key);
    }
}
