<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Enhavo\Bundle\MediaBundle\Entity\Format;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class RefreshFormatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:media:refresh-format')
            ->setDescription('Recreate Format')
            ->addOption('format', 'format', InputArgument::OPTIONAL, 'format to refresh')
            ->addOption('id', 'id', InputArgument::OPTIONAL, 'refresh only id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Refresh formats:');

        $options = [];

        $format = $input->getOption('format');
        if(!empty($format)) {
            $options['name'] = $format;
        }

        $id = $input->getOption('id');
        if(!empty($id)) {
            $id = $input->getOption('id');
            $options['file'] = $this->getContainer()->get('enhavo_media.repository.file')->find($id);
        }

        $formatManager = $this->getContainer()->get('enhavo_media.media.format_manager');
        $repository = $this->getContainer()->get('enhavo_media.repository.format');
        /** @var Format[] $formats */
        $formats = $repository->findBy($options);

        $progressBar = new ProgressBar($output, count($formats));
        $progressBar->start();

        foreach($formats as $format) {
            $progressBar->advance();
            $formatManager->applyFormat($format->getFile(), $format->getName());
        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('<info>Refreshing finished</info>');
    }
}