<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\ResourceBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeEnhavoGridCommand extends Command
{
    public function __construct(
        private $syliusResources,
        private $router,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('make:enhavo:resource')
            ->addOption('migrate', null, InputOption::VALUE_REQUIRED, 'Migrate from sylius resource')
            ->setDescription('Create resource config')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
