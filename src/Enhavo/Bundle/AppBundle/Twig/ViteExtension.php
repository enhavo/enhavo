<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Vite\ViteManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteExtension extends AbstractExtension
{
    public function __construct(
        private ViteManager $viteManager,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_js_tags', [$this, 'getJSTags'], ['is_safe' => ['html']]),
            new TwigFunction('vite_js_preload_tags', [$this, 'getJSPreloadTags'], ['is_safe' => ['html']]),
            new TwigFunction('vite_css_tags', [$this, 'getCSSTags'], ['is_safe' => ['html']]),
            new TwigFunction('vite_js_files', [$this, 'getJSTags']),
            new TwigFunction('vite_js_preload_files', [$this, 'getJSPreloadTags']),
            new TwigFunction('vite_css_files', [$this, 'getCSSTags']),
        ];
    }

    public function getJSTags(string $entrypoint, string $build): string
    {
        $output = [];
        foreach ($this->viteManager->getJSFiles($entrypoint, $build) as $file) {
            $output[] = sprintf('<script type="module" src="%s"></script>', $file);
        }
        return implode("\n", $output);
    }

    public function getJSPreloadTags(string $entrypoint, string $build): string
    {
        $output = [];
        foreach ($this->viteManager->getJSPreloadFiles($entrypoint, $build) as $file) {
            $output[] = sprintf('<link rel="modulepreload" href="%s" />', $file);
        }
        return implode("\n", $output);
    }

    public function getCSSTags(string $entrypoint, string $build): string
    {
        $output = [];
        foreach ($this->viteManager->getCSSFiles($entrypoint, $build) as $file) {
            $output[] = sprintf('<link rel="stylesheet" type="text/css" href="%s" />', $file);
        }
        return implode("\n", $output);
    }

    public function getJSFiles(string $entrypoint, string $build): array
    {
        return $this->viteManager->getJSFiles($entrypoint, $build);
    }

    public function getJSPreloadFiles(string $entrypoint, string $build): array
    {
        return $this->viteManager->getJSPreloadFiles($entrypoint, $build);
    }

    public function getCSSFiles(string $entrypoint, string $build): array
    {
        return $this->viteManager->getCSSFiles($entrypoint, $build);
    }
}
