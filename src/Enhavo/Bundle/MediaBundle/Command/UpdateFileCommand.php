<?php
/**
 * UpdateFileCommand.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFileCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:media:update-file')
            ->setDescription('Update file command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mediaManager = $this->getContainer()->get('enhavo_media.media.media_manager');

        $files = $mediaManager->findBy();
        $tokenGenerator = $this->getContainer()->get('enhavo.token_generator');

        foreach($files as $file)
        {
            if(!$file->getMd5Checksum()) {
                $file->setMd5Checksum(md5($file->getContent()->getContent()));
            }

            if(!$file->getToken()) {
                $file->setToken($tokenGenerator->generate(10));
            }
        }

        $this->getContainer()->get('doctrine.orm.default_entity_manager')->flush();
        $output->writeln('Files updated');
    }
}