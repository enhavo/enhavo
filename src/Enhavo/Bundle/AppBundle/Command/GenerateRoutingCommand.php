<?php
/**
 * GenerateRouting.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRoutingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:generate:routing')
            ->setDescription('Create default routing')
            ->addArgument(
                'app',
                InputArgument::REQUIRED,
                'What is the app name?'
            )
            ->addArgument(
                'resource',
                InputArgument::REQUIRED,
                'What is the name of the resource?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $input->getArgument('app');
        $resource = $input->getArgument('resource');

        $generator = $this->getContainer()->get('enhavo_admin.generator.route_generator');
        $outputCode = $generator->generate($app, $resource);

        $output->writeln($outputCode);
    }
}