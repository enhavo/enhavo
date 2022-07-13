<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\UserBundle\Mapper\UserMapperInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ChangePasswordCommand extends Command
{
    protected static $defaultName = 'enhavo:user:change-password';

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

        $username = $this->userMapper->getUsername($credentials);
        $user = $this->userRepository->loadUserByIdentifier($username);
        $user->setPlainPassword($password);
        $this->userManager->changePassword($user);

        $output->writeln(sprintf('Changed password for user <comment>%s</comment>', $username));
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
            $question = new Question('Please enter the new password:');
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
