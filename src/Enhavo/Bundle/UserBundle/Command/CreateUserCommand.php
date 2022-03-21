<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateUserCommand
 * @package Enhavo\Bundle\UserBundle\Command
 *
 * @property $userFactory UserFactory
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'enhavo:user:create';

    /** @var UserManager */
    private $userManager;

    /** @var FactoryInterface */
    private $userFactory;

    /** @var UserMapperInterface */
    private $userMapper;

    /**
     * CreateUserCommand constructor.
     * @param UserManager $userManager
     * @param FactoryInterface $userFactory
     * @param UserMapperInterface $userMapper
     */
    public function __construct(UserManager $userManager, FactoryInterface $userFactory, UserMapperInterface $userMapper)
    {
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->userMapper = $userMapper;

        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definition = [];
        $properties = $this->userMapper->getRegisterProperties();
        foreach ($properties as $property) {
            $definition[] = new InputArgument($property, InputArgument::REQUIRED, sprintf('The %s required for login', $property));
        }
        $definition[] = new InputArgument('password', InputArgument::REQUIRED, 'The password');
        $definition[] = new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin');
        $definition[] = new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive');

        $this
            ->setName('enhavo:user:create')
            ->setDescription('Create a user.')
            ->setDefinition($definition)
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
        $credentials = [];
        $properties = $this->userMapper->getRegisterProperties();
        foreach ($properties as $property) {
            $credentials[$property] = $input->getArgument($property);
        }

        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

        /** @var UserInterface $user */
        $user = $this->userFactory->createNew();
        $user->setPlainPassword($password);
        $user->setEnabled((bool) !$inactive);
        $user->setSuperAdmin((bool) $superadmin);
        $this->userMapper->mapValues($user, $credentials);
        $this->userManager->add($user);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $user->getUsername()));

        return Command::SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];
        $properties = $this->userMapper->getRegisterProperties();

        foreach ($properties as $property) {
            if (!$input->getArgument($property)) {
                $question = new Question(sprintf('Please choose a %s:', $property));
                $question->setValidator(function ($username) use ($property) {
                    if (empty($username)) {
                        throw new \Exception(sprintf('%s can not be empty', $property));
                    }

                    return $username;
                });
                $questions[$property] = $question;
            }
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
