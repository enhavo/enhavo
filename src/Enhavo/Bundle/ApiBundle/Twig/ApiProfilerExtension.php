<?php

namespace Enhavo\Bundle\ApiBundle\Twig;


use Enhavo\Bundle\ApiBundle\Profiler\JsonDumper;
use Symfony\Component\VarDumper\Cloner\Data;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ApiProfilerExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('json_dump', [$this, 'dumpJson']),
        ];
    }

    public function dumpJson(Data $data): ?string
    {
        $dumper = new JsonDumper();
        return $dumper->dump($data);
    }
}
