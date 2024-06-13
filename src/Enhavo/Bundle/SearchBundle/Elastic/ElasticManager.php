<?php

namespace Enhavo\Bundle\SearchBundle\Elastic;

use Symfony\Component\Filesystem\Filesystem;

class ElasticManager
{
    /** @var string */
    private $projectDir;

    /** @var Filesystem */
    private $fs;

    /**
     * ElasticManager constructor.
     * @param string $projectDir
     * @param Filesystem $fs
     */
    public function __construct(string $projectDir, Filesystem $fs)
    {
        $this->projectDir = $projectDir;
        $this->fs = $fs;
    }

    public function install($version, $context = null)
    {
        $targetDir = sprintf('%s/elasticsearch', $this->projectDir);
        if ($this->fs->exists($targetDir)) {
            $this->fs->remove($targetDir);
        }
        $url = sprintf("https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-%s.tar.gz", $version);
        $content = file_get_contents($url, false, $context);
        $tempname = tempnam("/tmp", "elasticsearch");
        $file = $tempname.'.tar.gz';
        file_put_contents($file, $content);

        $phar = new \PharData($file);
        $phar->decompress();

        $phar = new \PharData($tempname.'.tar');
        $phar->extractTo($this->projectDir);

        $versionParts = explode('-', $version);
        $this->fs->rename(sprintf('%s/elasticsearch-%s', $this->projectDir, $versionParts[0]), $targetDir);
        $this->fs->chmod(sprintf('%s/bin/elasticsearch', $targetDir), 0755);
    }

    public function existsInstallation()
    {
        $targetDir = sprintf('%s/elasticsearch', $this->projectDir);
        return $this->fs->exists($targetDir);
    }
}
