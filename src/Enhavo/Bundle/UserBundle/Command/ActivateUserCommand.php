<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivateUserCommand extends AbstractUserCommand
{
    protected static $defaultName = 'enhavo:user:activate';

    protected function configure()
    {
        $definitions = $this->getPropertyDefinitions();

        $this
            ->setName('enhavo:user:activate')
            ->setDescription('Activate a user')
            ->setDefinition($definitions)
            ->setHelp(<<<'EOT'
The <info>enhavo:user:activate</info> command activates a user (so they will be able to log in):

  <info>php %command.full_name% matthieu</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getUser($input);

        if (null === $user) {
            $output->writeln('<error> Can\'t find user! </error>');

            return Command::FAILURE;
        }

        $this->userManager->activate($user);

        $output->writeln(sprintf('User "%s" has been activated.', $user->getUserIdentifier()));

        return Command::SUCCESS;
    }
}
