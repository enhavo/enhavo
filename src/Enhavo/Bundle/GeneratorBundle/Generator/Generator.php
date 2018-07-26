<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as SensioGenerator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Generator extends SensioGenerator
{
    /**
     * @var EngineInterface
     */
    protected $twigEngine;

    public function __construct(EngineInterface $twigEngine)
    {
        $this->twigEngine = $twigEngine;
    }

    protected function camelCaseToSnakeCase($camelCaseName, $minusSeparator = false)
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

    protected function snakeCaseToCamelCase($snakeCaseName, $minusSeparator = false)
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

    protected function getBundleNameWithoutPostfix(BundleInterface $bundle)
    {
        $result = $bundle->getName();
        $matches = [];
        if (preg_match('/^(.+)Bundle$/', $result, $matches)) {
            $result = $matches[1];
        }
        return $result;
    }

    protected function renderFile($template, $target, $parameters)
    {
        self::mkdir(dirname($target));

        return self::dump($target, $this->twigEngine->render($template, $parameters));
    }
}
