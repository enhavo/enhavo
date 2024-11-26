<?php

namespace Enhavo\Bundle\AppBundle\Vite;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Filesystem\Path;

class ViteManager
{
    const MODE_TEST = 'test';
    const MODE_DEV = 'dev';
    const MODE_BUILD = 'build';

    private $testedBuilds = [];
    private $manifest = [];

    public function __construct(
        private array $builds,
        private string $publicPath,
        private string $mode = self::MODE_TEST,
    )
    {
    }

    public function getCSSFiles(string $entrypoint, string $build)
    {
        if ($this->isDev($entrypoint, $build)) {
            return []; // not needed on dev, it's inject by vite
        }
        return $this->getCSSUrls($entrypoint, $build);
    }

    public function getJSFiles(string $entrypoint, string $build): array
    {
        $files = [];
        if ($this->isDev($entrypoint, $build)) {
            $files[] = $this->getHost($build) . $this->getBase($build) . '/@vite/client';
            $files[] = $this->getHost($build) . $this->getBase($build) . '/' . $entrypoint;
        } else {
            $files[] = $this->getAssetUrl($entrypoint, $build);
        }
        return $files;
    }

    public function getJSPreloadFiles(string $entrypoint, string $build): array
    {
        if ($this->isDev($entrypoint, $build)) {
            return [];
        }
        return $this->getImportUrls($entrypoint, $build);
    }

    private function getHost(string $build)
    {
        return sprintf('http://%s:%s', $this->getBuildConfig($build)['host'], $this->getBuildConfig($build)['port']);
    }

    private function getBase(string $build)
    {
        $config = $this->getBuildConfig($build);
        return $config['base'] ?? '';
    }

    private function getBuildConfig(string $build): array
    {
        if (isset($this->builds[$build])) {
            return $this->builds[$build];
        }

        throw new \Exception(sprintf('Vite build with name "%s" doesn\'t exists', $build));
    }

    public function isDev(string $file, string $build): bool
    {
        if ($this->mode === self::MODE_BUILD) {
            return false;
        } else if ($this->mode === self::MODE_DEV) {
            return true;
        } else if ($this->mode !== self::MODE_TEST) {
            throw new \Exception(sprintf('Vite mode must be %s, %s or %s but "%s" given', self::MODE_BUILD, self::MODE_DEV, self::MODE_TEST, $this->mode));
        }

        if (isset($this->testedBuilds[$build])) {
            return $this->testedBuilds[$build];
        }

        $success = $this->testDev($build, $file);

        $this->testedBuilds[$build] = $success;
        return $success;
    }

    public function testDev(string $build, string $file): bool
    {
        if (!str_starts_with($file, '/')) {
            $file = '/' . $file;
        }

        try {
            $client = HttpClient::create();
            $url = $this->getHost($build) . $this->getBase($build) . $file;
            $response = $client->request('GET', $url);

            return in_array($response->getStatusCode(), [200,500]);
        } catch (TransportException $e) {
            return false;
        }
    }

    private function getAssetUrl(string $entrypoint, string $build)
    {
        $manifest = $this->getManifest($build);
        $buildPath = $this->getBuildPath($build);

        return isset($manifest[$entrypoint])
            ? $buildPath . '/' . $manifest[$entrypoint]['file']
            : '';
    }

    private function getImportUrls(string $entrypoint, string $build): array
    {
        $urls = [];
        $manifest = $this->getManifest($build);
        $buildPath = $this->getBuildPath($build);

        if (!empty($manifest[$entrypoint]['imports'])) {
            foreach ($manifest[$entrypoint]['imports'] as $imports) {
                $urls[] = $buildPath . '/' . $manifest[$imports]['file'];
            }
        }

        return $urls;
    }

    private function getCSSUrls(string $entrypoint, string $build): array
    {
        $urls = [];
        $manifest = $this->getManifest($build);
        $buildPath = $this->getBuildPath($build);

        if (!empty($manifest[$entrypoint]['css'])) {
            foreach ($manifest[$entrypoint]['css'] as $file) {
                $urls[] = $buildPath . '/' . $file;
            }
        }

        return $urls;
    }

    private function getBuildPath(string $build)
    {
        $publicPath = Path::canonicalize($this->publicPath);
        $buildPath = Path::canonicalize($this->getManifestPath($build).'/../../');
        return substr($buildPath, strlen($publicPath));
    }

    private function getManifest(string $build): array
    {
        if (isset($this->manifest[$build])) {
            return $this->manifest[$build];
        }

        $manifestPath = $this->getManifestPath($build);

        $content = file_get_contents($manifestPath);
        $manifest = json_decode($content, true);
        $this->manifest[$build] = $manifest;
        return $manifest;
    }

    private function getManifestPath(string $build): string
    {
        $manifestPath = $this->getBuildConfig($build)['manifest'];
        if (!file_exists($manifestPath)) {
            throw new \Exception(sprintf('Vite manifest file not found at "%s', $manifestPath));
        }
        return $manifestPath;
    }
}
