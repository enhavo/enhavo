<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 26/08/16
 * Time: 10:35
 */

namespace Enhavo\Bundle\SettingBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Enhavo\Bundle\SettingBundle\Provider\DatabaseProvider;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class InitSettingCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('enhavo:setting:init')

            // the short description shown while running "php bin/console list"
            ->setDescription('Initializes settings.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command initializes settings.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Initializing settings',
            '============',
            '',
        ]);
        $db_provider = $this->container->get('enhavo_setting.provider.database_provider');
        $db_provider->init();
    }
}