<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 26/08/16
 * Time: 10:35
 */

namespace Enhavo\Bundle\SettingBundle\Command;

use Enhavo\Bundle\SettingBundle\Provider\DatabaseProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitSettingCommand extends Command
{
    /**
     * @var DatabaseProvider
     */
    private $provider;

    /**
     * InitSettingCommand constructor.
     * @param DatabaseProvider $provider
     */
    public function __construct(DatabaseProvider $provider)
    {
        $this->provider = $provider;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:setting:init')
            ->setDescription('Initializes settings.')
            ->setHelp("This command initializes settings.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Initializing settings',
            '============',
            '',
        ]);
        $this->provider->init();
    }
}
