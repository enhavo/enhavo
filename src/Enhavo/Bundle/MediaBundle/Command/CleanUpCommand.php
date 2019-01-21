<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CleanUpCommand extends Command
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $mediaPath;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * CleanUpCommand constructor.
     * @param Filesystem $fs
     * @param string $mediaPath
     * @param MediaManager $mediaManager
     */
    public function __construct(Filesystem $fs, string $mediaPath, MediaManager $mediaManager)
    {
        $this->fs = $fs;
        $this->mediaPath = $mediaPath;
        $this->mediaManager = $mediaManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:clean-up')
            ->setDescription('Clean up unused media files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        $finder->files()->in($this->mediaPath);
        foreach ($finder as $file) {
            $name = $file->getFilename();
            if(preg_match('#^[0-9]+$#', $name)) {
                $meta = $this->mediaManager->find($name);
                if($meta === null) {
                    $this->fs->remove($file->getRealPath());
                }
            }
        }
    }
}
