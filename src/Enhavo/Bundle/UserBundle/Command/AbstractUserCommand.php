<?php

namespace Enhavo\Bundle\UserBundle\Command;

use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderInterface;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractUserCommand extends Command
{
    public function __construct(
        protected UserManager $userManager,
        protected RepositoryInterface $userRepository,
        protected UserIdentifierProviderResolver $resolver,
        protected string $userClass,
    )
    {
        parent::__construct();
    }

    protected function getPropertyDefinitions()
    {
        $definitions = [];

        $definitions[] = new InputArgument('user_identifier', InputArgument::REQUIRED, 'Value to identify a single user');
        $definitions[] = new InputOption('user_class', null, InputOption::VALUE_REQUIRED, 'Select a different user class');

        $properties = $this->getAllProperties();
        foreach ($properties as $property) {
            $definitions[] = new InputOption($property, null, InputOption::VALUE_REQUIRED, $property);
        }

        return $definitions;
    }

    protected function getMissingPropertyQuestions(InputInterface $input)
    {
        $questions = [];
        $properties = $this->getProvider($input)->getUserIdentifierProperties();

        foreach ($properties as $property) {
            if (!$input->getOption($property)) {
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

        return $questions;
    }

    protected function getUser(InputInterface $input)
    {
        $values = $this->getPropertyValues($input);
        $identifier = $this->getProvider($input)->getUserIdentifierByPropertyValues($values);
        return $this->userRepository->loadUserByIdentifier($identifier);
    }

    protected function getPropertyValues(InputInterface $input)
    {
        $values = [];

        $properties = $this->getAllProperties();
        foreach ($properties as $property) {
            $value = $input->getOption($property);
            if ($value) {
                $values[$property] = $value;
            }
        }

        return $values;
    }

    protected function getProvider(InputInterface $input): UserIdentifierProviderInterface
    {
        $userClass = $input->getOption('user_class');
        $userClass = isset($userClass) ? $userClass : $this->userClass;
        return $this->resolver->getProviderByClass($userClass);
    }

    private function getAllProperties(): array
    {
        $properties = [];
        foreach ($this->resolver->getProviders() as $provider) {
            foreach ($provider->getUserIdentifierProperties() as $property) {
                if (!in_array($property, $properties)) {
                    $properties[] = $property;
                }
            }
        }
        return $properties;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = $this->getMissingPropertyQuestions($input);

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setOption($name, $answer);
        }

        if ($input->getArgument('user_identifier') === null) {
            $userIdentifier = $this->getProvider($input)->getUserIdentifierByPropertyValues($this->getPropertyValues($input));
            $input->setArgument('user_identifier', $userIdentifier);
        }
    }

    protected function addPasswordQuestion(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('password')) {
            $question = new Question('Please enter the new password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('password', $answer);
        }
    }
}
