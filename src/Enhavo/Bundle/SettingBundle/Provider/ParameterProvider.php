<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 26/08/16
 * Time: 12:09
 */

namespace Enhavo\Bundle\SettingBundle\Provider;
use Enhavo\Bundle\SettingBundle\Exception\ReadOnlyException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ParameterProvider implements ProviderInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSetting($key)
    {
        return $this->container->getParameter($key);
    }

    public function hasSetting($key)
    {
        return $this->container->hasParameter($key);
    }

    public function setSetting($key, $value)
    {
        throw new ReadOnlyException();
    }
}