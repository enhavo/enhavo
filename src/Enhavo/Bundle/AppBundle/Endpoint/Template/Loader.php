<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    public function __construct(
        private readonly string $dataPath,
        private readonly ExpressionLanguage $expressionLanguage,
    )
    {
        $expressionLanguage = new ExpressionLanguage();

        $expressionLanguage->register('include', function ($file): string {
            return '$loader->load($file)';
        }, function ($arguments, $file): mixed {
            return $this->load($file);
        });
    }

    public function merge(&$target, $source, ?bool $recursive = false, ?int $depth = null)
    {
        foreach ($source as $key => $value) {
            if ($recursive && ($depth === null || $depth > 0 ) && isset($target[$key]) && $this->isArrayLike($target[$key]) && $this->isArrayLike($value)) {
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

    public function load($file): mixed
    {
        $path = realpath($this->dataPath) . '/' . $file;
        if (!file_exists($path)) {
            throw new \Exception(sprintf('File "%s" not exists', $file));
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $fileData = match ($ext) {
            'yml', 'yaml' => $this->loadYamlFile($path),
            'json' => $this->loadJsonFile($path),
            'php' => $this->loadPHPFile($path),
            default => throw new \Exception(sprintf('Extension "%s" not supported', $ext))
        };

        $fileData = $this->render($fileData);

        return $fileData;
    }

    private function render(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->render($value);
            }
        } else if (is_string($data)) {
            if (str_starts_with($data, 'expr:')) {
                $data = $this->expressionLanguage->evaluate(substr($data, 5));
            }
        }

        return $data;
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
