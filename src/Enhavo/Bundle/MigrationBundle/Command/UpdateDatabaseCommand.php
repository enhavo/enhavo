<?php
/**
 * UpdateDatabaseCommand.php
 *
 * @since 2016-04-15
 * @author Fabian Liebl <fabian.liebl@xq-web.de>
 */

namespace Enhavo\Bundle\MigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:db:update')
            ->setDescription('Update database version')
//            ->addOption(
//                'version',
//                null,
//                InputOption::VALUE_REQUIRED,
//                'Which version do you want to update to?'
//            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $targetVersion = $input->getOption('version');
        // TODO: Create generic version update script

        $output->writeln('Starting script');

        $this->getContainer()->get('enhavo_migration.db_media_connections_converter')->convertDatabase($output);

        $output->writeln('Finished');
    }
}
