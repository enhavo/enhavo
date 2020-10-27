<?php

namespace Enhavo\Bundle\UserBundle\Command;

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

    /**
     * ChangePasswordCommand constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     */
    public function __construct(UserManager $userManager, UserRepository $userRepository)
    {
        parent::__construct();
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('enhavo:user:change-password')
            ->setDescription('Change the password of a user.')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username/email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ))
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
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $user = $this->userRepository->findByUsername($username) ?? $this->userRepository->findByEmail($username);
        $user->setPlainPassword($password);
        $this->userManager->changePassword($user);

        $output->writeln(sprintf('Changed password for user <comment>%s</comment>', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Please give the username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
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
