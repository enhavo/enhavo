<?php
/**
 * InstallCommand.php
 *
 * @since 05/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Command;

use Enhavo\Bundle\InstallerBundle\Fixtures\DemoFixtures;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesLoadCommand extends Command
{
    /**
     * @var DemoFixtures
     */
    private $fixtures;

    /**
     * FixturesLoadCommand constructor.
     * @param DemoFixtures $fixtures
     */
    public function __construct(DemoFixtures $fixtures)
    {
        $this->fixtures = $fixtures;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('enhavo:install:fixtures')
            ->setDescription('Install Fixtures')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->fixtures->loadFixtures();
    }
}
