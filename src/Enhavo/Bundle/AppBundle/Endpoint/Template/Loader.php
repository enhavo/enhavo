<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Template;

use Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage\TemplateExpressionLanguageEvaluator;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    private array $cache = [];

    public function __construct(
        private readonly string $dataPath,
        private readonly TemplateExpressionLanguageEvaluator $templateExpressionLanguageEvaluator,
    ) {
    }

    public function merge(&$target, $source, bool $recursive = false, ?int $depth = null)
    {
        foreach ($source as $key => $value) {
            if ($recursive && (null === $depth || $depth > 0) && isset($target[$key]) && $this->isArrayLike($target[$key]) && $this->isArrayLike($value)) {
                $depth = is_numeric($depth) ? $depth - 1 : null;
                // if we have array like data, we can't pass it directly as a reference parameter, so lets save it
                // temporarily in a variable and write the values back later
                $temp = $target[$key];
                $this->merge($temp, $value, $recursive, $depth);
                $target[$key] = $temp;
            } else {
                $target[$key] = $value;
            }
        }
    }

    private function isArrayLike($data): bool
    {
        if (is_array($data)) {
            return true;
        } elseif ($data instanceof \ArrayAccess && $data instanceof \Traversable) {
            return true;
        }

        return false;
    }

    public function load(array|string $files, bool $recursive = false, ?int $depth = null): mixed
    {
        $data = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $this->merge($data, $this->loadFile($file), $recursive, $depth);
            }
        } elseif (is_string($files)) {
            $this->merge($data, $this->loadFile($files), $recursive, $depth);
        }

        return $data;
    }

    private function loadFile($file): mixed
    {
        $path = realpath($this->dataPath).'/'.$file;

        if (isset($this->cache[$path])) {
            return $this->cache[$path];
        }

        if (!file_exists($path)) {
            throw new \Exception(sprintf('File "%s" does not exist', $file));
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $fileData = match ($ext) {
            'yml', 'yaml' => $this->loadYamlFile($path),
            'json' => $this->loadJsonFile($path),
            'php' => $this->loadPHPFile($path),
            default => throw new \Exception(sprintf('Extension "%s" not supported', $ext)),
        };

        $fileData = $this->templateExpressionLanguageEvaluator->evaluate($fileData);

        $this->cache[$path] = $fileData;

        return $fileData;
    }

    private function loadYamlFile($path)
    {
        return Yaml::parse(file_get_contents($path));
    }

    private function loadJsonFile($path)
    {
        return json_decode(file_get_contents($path), true);
    }

    private function loadPHPFile($path)
    {
        $callable = include $path;
        if (!is_callable($callable)) {
            throw new \Exception(sprintf('The file "%s" must return a callable', $path));
        }

        return $callable($this);
    }
}
