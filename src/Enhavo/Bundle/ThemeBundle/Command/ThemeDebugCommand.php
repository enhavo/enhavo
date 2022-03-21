<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-14
 * Time: 19:24
 */

namespace Enhavo\Bundle\ThemeBundle\Command;

use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ThemeDebugCommand extends Command
{
    /**
     * @var ThemeManager
     */
    private $manager;


    public function __construct(ThemeManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:theme')
            ->setDescription('Debug enhavo theme');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Check themes');

        $themes = $this->manager->getThemes();
        foreach($themes as $theme) {
            $output->writeln(sprintf('Found theme "%s"', $theme->getKey()));
        }
        return Command::SUCCESS;
    }
}
