<?php
/**
 * InstallCommand.php
 *
 * @since 05/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\UserBundle\Entity\User;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InstallCommand extends Command
{
    /**
     * @var EntityRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var string
     */
    private $projectPath;

    /**
     * @var EngineInterface
     */
    private $template;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * InstallCommand constructor.
     * @param EntityRepositoryInterface $userRepository
     * @param UserManager $userManager
     * @param FactoryInterface $userFactory
     * @param ValidatorInterface $validator
     * @param string $projectPath
     * @param EngineInterface $template
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityRepositoryInterface $userRepository,
        UserManager $userManager,
        FactoryInterface $userFactory,
        ValidatorInterface $validator,
        string $projectPath,
        EngineInterface $template,
        EntityManagerInterface $em
    ) {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->userFactory = $userFactory;
        $this->validator = $validator;
        $this->projectPath = $projectPath;
        $this->template = $template;
        $this->em = $em;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('enhavo:install')
            ->setDescription('Installer')
            ->addOption('user', null, InputOption::VALUE_REQUIRED, 'Admin username')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'Admin password')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Admin email address')
            ->addOption('no-bundle')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->installDatabase($input, $output);
        $this->insertUser($input, $output);
        $this->createBundle($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function installDatabase(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Update Database</info>');
        $command = $this->getApplication()->find('doctrine:schema:update');

        $arguments = array(
            '--force'  => true,
        );

        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function insertUser(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Create admin user</info>');
        $helper = $this->getHelper('question');

        $email = $input->getOption('email');
        $password = $input->getOption('password');

        if(empty($email)) {
            $email = $this->askForEmail($input, $output);
        }

        if(empty($password)) {
            $password = $this->askForPassword($input, $output);
        }

        $question = new ConfirmationQuestion('<info>Is this information correct</info> [<comment>yes</comment>]?', true);

        if ($helper->ask($input, $output, $question)) {
            $this->createUser($email, $password);
            $output->writeln('User created');
        } else {
            $output->writeln('Aborted');
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    private function askForEmail(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('<question>Admin email:</question> ');
        $email = $helper->ask($input, $output, $question);

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if(!empty($user)) {
            $output->writeln('<error>Email already exists!</error>');
            return $this->askForEmail($input, $output);
        }
        if(!$this->isEmailValid($email)) {
            $output->writeln('<error>Email is invalid</error>');
            return $this->askForEmail($input, $output);
        }
        return $email;
    }

    /**
     * Validate single email
     *
     * @param string $email
     *
     * @return boolean
     */
    private function isEmailValid($email)
    {
        $constraints = array(
            new \Symfony\Component\Validator\Constraints\Email(),
            new \Symfony\Component\Validator\Constraints\NotBlank()
        );

        $errors = $this->validator->validate($email, $constraints);
        return count($errors) === 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    private function askForPassword(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('<question>Admin password:</question> ');
        $password = $helper->ask($input, $output, $question);

        if(empty($password)) {
            $output->writeln('<error>Password shouldn\'t be empty</error>');
            return $this->askForPassword($input, $output);
        }
        return $password;
    }

    /**
     * @param $email
     * @param $password
     */
    protected function createUser($email, $password)
    {
        /** @var User $user */
        $user = $this->userFactory->createNew();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->addRole('ROLE_SUPER_ADMIN');
        $this->userManager->updateCanonicalFields($user);
        $this->userManager->updatePassword($user);
        $this->em->persist($user);
        $this->em->flush();
    }

    private function createBundle(InputInterface $input, OutputInterface $output)
    {
        $noBundle = $input->getOption('no-bundle');
        if($noBundle) {
            return;
        }

        $srcDir = sprintf('%s/src', $this->projectPath);
        $projectBundleDir = sprintf('%s/ProjectBundle', $srcDir);

        if(file_exists($projectBundleDir)) {
            $output->writeln('Project Bundle already exists, nothing to do here');
            return;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('<info>Do you want to add a ProjectBundle</info> [<comment>yes</comment>]?', true);

        if ($helper->ask($input, $output, $question)) {
            $command = $this->getApplication()->find('generate:bundle');

            $arguments = array(
                '--namespace'  => 'ProjectBundle',
                '--bundle-name'  => 'ProjectBundle',
                '--dir'  => realpath($srcDir),
                '--format'  => 'yml'
            );

            $argumentInputs = new ArrayInput($arguments);
            $command->run($argumentInputs, $output);
            $this->overwriteProjectBundle(realpath($projectBundleDir));
        }
    }

    private function overwriteProjectBundle($projectBundleDir)
    {
        $defaultControllerContent = $this->template->render('EnhavoInstallerBundle:generate:DefaultController.php.twig');
        file_put_contents(sprintf('%s/Controller/DefaultController.php', $projectBundleDir), $defaultControllerContent);

        $routingContent = $this->template->render('EnhavoInstallerBundle:generate:routing.yml.twig');
        file_put_contents(sprintf('%s/Resources/config/routing.yml', $projectBundleDir), $routingContent);

        $indexContent = $this->template->render('EnhavoInstallerBundle:generate:index.html.twig');
        file_put_contents(sprintf('%s/Resources/views/Default/index.html.twig', $projectBundleDir), $indexContent);
    }
}
