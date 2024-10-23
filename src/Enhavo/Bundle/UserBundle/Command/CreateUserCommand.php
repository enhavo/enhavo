<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderResolver;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class CreateUserCommand
 * @package Enhavo\Bundle\UserBundle\Command
 *
 * @property $userFactory UserFactory
 */
class CreateUserCommand extends AbstractUserCommand
{
    protected static $defaultName = 'enhavo:user:create';

    public function __construct(
        UserManager $userManager,
        UserRepository $userRepository,
        UserIdentifierProviderResolver $resolver,
        string $userClass,
        private FactoryInterface $userFactory
    )
    {
        parent::__construct($userManager, $userRepository, $resolver, $userClass);
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definitions = $this->getPropertyDefinitions();
        $definitions[] = new InputArgument('password', InputArgument::REQUIRED, 'The password');
        $definitions[] = new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin');
        $definitions[] = new InputOption('disable', null, InputOption::VALUE_NONE, 'Disable user');

        $this
            ->setName('enhavo:user:create')
            ->setDescription('Create a user.')
            ->setDefinition($definitions)
            ->setHelp(<<<'EOT'
The <info>enhavo:user:create</info> command creates a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will ask you for credentials and then a password.

You can alternatively specify the credentials and password as the following arguments:

  <info>php %command.full_name% matthieu@example.com mypassword</info>

You can create a super admin via the super-admin flag:

  <info>php %command.full_name% admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% thibault --inactive</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();

        $this->applyProperties($input, $user);
        $password = $input->getArgument('password');
        $disable = $input->getOption('disable');
        $superAdmin = (bool)$input->getOption('super-admin');

        $user->setPlainPassword($password);
        $user->setEnabled(!$disable);
        $user->setSuperAdmin($superAdmin);

        $this->userManager->add($user);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $user->getUsername()));

        return Command::SUCCESS;
    }

    private function applyProperties(InputInterface $input, UserInterface $user)
    {
        $values = [];
        $properties = $this->getProvider($input)->getUserIdentifierProperties();
        foreach ($properties as $property) {
            $values[$property] = $input->getOption($property);
        }

        $propertyAccessor = new PropertyAccessor();
        foreach ($values as $property => $value) {
            if ($propertyAccessor->isWritable($user, $property)) {
                $value = $values[$property];
                $propertyAccessor->setValue($user, $property, $value);
            } else {
                throw new PropertyNotExistsException(sprintf('Error while setting property "%s"', $property));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        $this->addPasswordQuestion($input, $output);
    }
}
