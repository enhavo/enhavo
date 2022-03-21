<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RoleCommand extends Command
{
    protected static $defaultName = 'enhavo:user:role';

    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserMapperInterface */
    private $userMapper;

    /**
     * ChangePasswordCommand constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     * @param UserMapperInterface $userMapper
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository, UserMapperInterface $userMapper)
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definitions = [];
        $properties = $this->userMapper->getRegisterProperties();
        foreach ($properties as $property) {
            $definitions[] = new InputArgument($property, InputArgument::REQUIRED, sprintf('The %s required for login', $property));
        }

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
        $credentials = [];
        $properties = $this->userMapper->getRegisterProperties();
        foreach ($properties as $property) {
            $credentials[$property] = $input->getArgument($property);
        }

        $role = $input->getArgument('role');
        $remove = $input->getOption('remove');

        $username = $this->userMapper->getUsername($credentials);
        $user = $this->userRepository->loadUserByUsername($username);

        if ($remove) {
            $user->removeRole($role);
            $output->writeln(sprintf('Remove role <comment>%s</comment> for user <comment>%s</comment>', $role, $username));
        } else {
            $user->addRole($role);
            $output->writeln(sprintf('Add role <comment>%s</comment> for user <comment>%s</comment>', $role, $username));
        }

        $this->userManager->update($user);
        return Command::SUCCESS;
    }
}
