<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Command;

use Enhavo\Bundle\CalendarBundle\Import\ImportManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'enhavo:calendar:import',
    description: 'import',
)]
class ImportCommand extends Command
{
    /**
     * @var ImportManager
     */
    private $manager;

    /**
     * ImportCommand constructor.
     */
    public function __construct(ImportManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->manager->import();

        return Command::SUCCESS;
    }
}
