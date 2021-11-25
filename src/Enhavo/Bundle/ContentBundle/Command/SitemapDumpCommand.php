<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

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

    /** @var string */
    private $path;

    /** @var Filesystem */
    private $fs;

    /**
     * @param SitemapGenerator $sitemapGenerator
     * @param string $path
     * @param Filesystem $fs
     */
    public function __construct(SitemapGenerator $sitemapGenerator, string $path, Filesystem $fs)
    {
        parent::__construct();
        $this->sitemapGenerator = $sitemapGenerator;
        $this->path = $path;
        $this->fs = $fs;
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:sitemap:dump')
            ->setDescription('Dump the sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fs->dumpFile($this->path, $this->sitemapGenerator->generate());
    }
}
