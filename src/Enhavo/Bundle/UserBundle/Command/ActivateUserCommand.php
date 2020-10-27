<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ActivateUserCommand extends Command
{
    protected static $defaultName = 'enhavo:user:activate';

    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /**
     * ActivateUserCommand constructor.
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
            ->setName('enhavo:user:activate')
            ->setDescription('Activate a user')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username/email'),
            ))
            ->setHelp(<<<'EOT'
The <info>enhavo:user:activate</info> command activates a user (so they will be able to log in):

  <info>php %command.full_name% matthieu</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $user = $this->userRepository->findByUsername($username) ?? $this->userRepository->findByEmail($username);

        $this->userManager->activate($user);

        $output->writeln(sprintf('User "%s" has been activated.', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);

            $input->setArgument('username', $answer);
        }
    }
}
