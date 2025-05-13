<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Command;

use Enhavo\Bundle\AppBundle\Init\InitManager;
use Enhavo\Bundle\CalendarBundle\Import\ImportManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * @var ImportManager
     */
    private $manager;

    /**
     * InitCommand constructor.
     */
    public function __construct(InitManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:init')
            ->setDescription('Initialize enhavo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager->init($output);

        return Command::SUCCESS;
    }
}
