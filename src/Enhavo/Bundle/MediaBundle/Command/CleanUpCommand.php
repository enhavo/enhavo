<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CleanUpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:media:clean-up')
            ->setDescription('Clean up unused media files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mediaManager = $this->getContainer()->get('enhavo_media.media.media_manager');

        $mediaPath = sprintf('%s/media', $this->getContainer()->getParameter('kernel.root_dir'));
        $fs = $this->getContainer()->get('filesystem');

        $finder = new Finder();
        $finder->files()->in($mediaPath);
        foreach ($finder as $file) {
            $name = $file->getFilename();
            if(preg_match('#^[0-9]+$#', $name)) {
                $meta = $mediaManager->find($name);
                if($meta === null) {
                    $fs->remove($file->getRealPath());
                }
            }
        }
    }
}