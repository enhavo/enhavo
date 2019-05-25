<?php

namespace Enhavo\Bundle\AppBundle\Maker;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MakerUtil
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * MakerUtil constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function camelCaseToSnakeCase($camelCaseName, $minusSeparator = false)
    {
        $lcCamelCaseName = lcfirst($camelCaseName);
        $snakeCasedName = '';

        if ($minusSeparator) {
            $separator = '-';
        } else {
            $separator = '_';
        }

        $len = strlen($lcCamelCaseName);
        for ($i = 0; $i < $len; ++$i) {
            if (ctype_upper($lcCamelCaseName[$i])) {
                $snakeCasedName .= $separator . strtolower($lcCamelCaseName[$i]);
            } else {
                $snakeCasedName .= strtolower($lcCamelCaseName[$i]);
            }
        }

        return $snakeCasedName;
    }

    public function snakeCaseToCamelCase($snakeCaseName, $minusSeparator = false)
    {
        $ucSnakeCaseName = ucfirst($snakeCaseName);
        $camelCasedName = '';

        if ($minusSeparator) {
            $separator = '-';
        } else {
            $separator = '_';
        }

        $nextUpperCase = false;
        $len = strlen($ucSnakeCaseName);
        for ($i = 0; $i < $len; ++$i) {
            if ($ucSnakeCaseName[$i] == $separator) {
                $nextUpperCase = true;
            } else {
                if ($nextUpperCase) {
                    $nextUpperCase = false;
                    $camelCasedName .= strtoupper($ucSnakeCaseName[$i]);
                } else {
                    $camelCasedName .= $ucSnakeCaseName[$i];
                }
            }
        }

        return $camelCasedName;
    }

    public function getBundleNameWithoutPostfix($bundle)
    {
        $result = $bundle;
        if($bundle instanceof BundleInterface) {
            $result = $bundle->getName();
        }

        $matches = [];
        if (preg_match('/^(.+)Bundle$/', $result, $matches)) {
            $result = $matches[1];
        }
        return $result;
    }

    public function getBundlePath($bundleName)
    {
        return $this->kernel->locateResource(sprintf('@%s', $bundleName));
    }

    public function existsBundle($bundleName)
    {

    }

    public function getBundleUrl($bundleName)
    {
        return preg_replace('/_/', '/', $this->camelCaseToSnakeCase($this->getBundleNameWithoutPostfix($bundleName)));
    }

    public function getBundleNamespace($bundleName)
    {
        $bundle = $this->kernel->getBundle($bundleName);
        $class = get_class($bundle);
        $namespace = substr($class, 0, strlen($class) -  strlen($bundleName) - 1);
        return $namespace;
    }

    public function getResourceUrl($name)
    {
        return $this->camelCaseToSnakeCase($name, true);
    }

    public function getRealpath($path)
    {
        return $this->kernel->locateResource($path);
    }
}
