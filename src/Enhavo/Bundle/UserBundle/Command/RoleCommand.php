<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class RoleCommand extends AbstractUserCommand
{
    protected static $defaultName = 'enhavo:user:role';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definitions = $this->getPropertyDefinitions();
        $definitions[] = new InputArgument('role', InputArgument::REQUIRED, 'The role');
        $definitions[] = new InputOption('remove', null, InputOption::VALUE_NONE, 'Remove role');

        $this
            ->setName('enhavo:user:role')
            ->setDescription('Add or remove role of a user.')
            ->setDefinition($definitions)
            ->setHelp(<<<'EOT'
The <info>enhavo:user:role</info> command adding and remove a role:

  <comment>Add a role:</comment>
  <info>php %command.full_name% matthieu ROLE_SUPER_ADMIN</info>

  <comment>Remove a role:</comment>
  <info>php %command.full_name% matthieu ROLE_SUPER_ADMIN --remove</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getUser($input);

        if ($user === null) {
            $output->writeln('<error> Can\'t find user! </error>');
            return Command::FAILURE;
        }

        $role = $input->getArgument('role');
        $remove = $input->getOption('remove');

        if ($remove) {
            $user->removeRole($role);
            $output->writeln(sprintf('Remove role <comment>%s</comment> for user <comment>%s</comment>', $role, $user->getUserIdentifier()));
        } else {
            $user->addRole($role);
            $output->writeln(sprintf('Add role <comment>%s</comment> for user <comment>%s</comment>', $role, $user->getUserIdentifier()));
        }

        $this->userManager->update($user);
        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        if (!$input->getArgument('role')) {
            $question = new Question('Please choose a role:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Role can\'t be empty');
                }

                return $password;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('role', $answer);
        }
    }
}
