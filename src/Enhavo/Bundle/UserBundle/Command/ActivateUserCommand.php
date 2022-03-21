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

class ActivateUserCommand extends Command
{
    protected static $defaultName = 'enhavo:user:activate';

    /** @var UserManager */
    private $userManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserMapperInterface */
    private $userMapper;

    /**
     * ActivateUserCommand constructor.
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

        $username = $this->userMapper->getUsername($credentials);
        $user = $this->userRepository->loadUserByUsername($username);

        $this->userManager->activate($user);

        $output->writeln(sprintf('User "%s" has been activated.', $username));

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

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
