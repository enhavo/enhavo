<?php

namespace Enhavo\Bundle\ContentBundle\Command;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapDumpCommand extends Command
{
    /** @var SitemapGenerator */
    private $sitemapGenerator;

    /** @var Filesystem */
    private $fs;

    /** @var string */
    private $path;

    /** @var string */
    private $projectDir;

    public function __construct(SitemapGenerator $sitemapGenerator, Filesystem $fs, string $path, string $projectDir)
    {
        parent::__construct();
        $this->sitemapGenerator = $sitemapGenerator;
        $this->fs = $fs;
        $this->path = $path;
        $this->projectDir = $projectDir;
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:sitemap:dump')
            ->setDescription('Dump the sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sitemap.xml ...');

        $fullPath = sprintf('%s/%s', $this->projectDir, $this->path);
        $this->fs->dumpFile($fullPath, $this->sitemapGenerator->generate());

        $output->writeln(sprintf('Sitemap file created in "%s"', $this->path));
    }
}
