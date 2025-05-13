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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangePasswordCommand extends AbstractUserCommand
{
    protected static $defaultName = 'enhavo:user:change-password';

    protected function configure()
    {
        $definitions = $this->getPropertyDefinitions();
        $definitions[] = new InputArgument('password', InputArgument::REQUIRED, 'The password');

        $this
            ->setName('enhavo:user:change-password')
            ->setDescription('Change the password of a user.')
            ->setDefinition($definitions)
            ->setHelp(<<<'EOT'
The <info>enhavo:user:change-password</info> command changes the password of a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will first ask you for a password.

You can alternatively specify the password as a second argument:

  <info>php %command.full_name% matthieu mypassword</info>

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

        $password = $input->getArgument('password');

        $user->setPlainPassword($password);
        $this->userManager->changePassword($user);

        $output->writeln(sprintf('Changed password for user <comment>%s</comment>', $user->getUserIdentifier()));

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $this->addPasswordQuestion($input, $output);
    }
}
