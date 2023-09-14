<?php

namespace Enhavo\Bundle\AppBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class TemplateEndpointType extends AbstractEndpointType
{
    public function __construct(
        public string $dataPath
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (is_array($options['load'])) {
            foreach ($options['load'] as $file) {
                $this->loadFile($data, $file);
            }
        } else if (is_string($options['load'])) {
            $this->loadFile($data, $options['load']);
        }

        if (is_array($options['data'])) {
            foreach ($options['data'] as $key => $value) {
                $data->set($key, $value);
            }
        }
    }

    private function loadFile(Data $data, $file)
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

        if (!is_array($fileData)) {
            throw new \Exception(sprintf('The file "%s" must return an array', $file));
        }

        foreach ($fileData as $key => $value) {
            $data->set($key, $value);
        }
    }

    private function loadYamlFile($path)
    {
        return Yaml::parse(file_get_contents($path));
    }

    private function loadJsonFile($path)
    {
        return json_decode(file_get_contents($path));
    }

    private function loadPHPFile($path)
    {
        $callable = include $path;
        if (!is_callable($callable)) {
            throw new \Exception(sprintf('The file "%s" must return a callable', $path));
        }

        return $callable();
    }

    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => null,
            'load' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
