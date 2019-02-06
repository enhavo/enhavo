<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Generator
{
    /**
     * @var EngineInterface
     */
    private $twigEngine;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * Generator constructor.
     * @param EngineInterface $twigEngine
     * @param Filesystem $fs
     */
    public function __construct(EngineInterface $twigEngine, Filesystem $fs)
    {
        $this->twigEngine = $twigEngine;
        $this->fs = $fs;
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

    public function getBundleNameWithoutPostfix(BundleInterface $bundle)
    {
        $result = $bundle->getName();
        $matches = [];
        if (preg_match('/^(.+)Bundle$/', $result, $matches)) {
            $result = $matches[1];
        }
        return $result;
    }

    public function renderFile($template, $target, $parameters)
    {
        $this->fs->mkdir(dirname($target));
        $this->fs->dumpFile($target, $this->twigEngine->render($template, $parameters));
    }

    public function render($template, $parameters)
    {
        return $this->twigEngine->render($template, $parameters);
    }
}
