<?php
/**
 * GenerateExtendCommand.php
 *
 * @since 06/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExtendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:extend')
            ->setDescription('Extend class')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'From which class you want to extend?'
            )
            ->addArgument(
                'target',
                InputArgument::REQUIRED,
                'What is the name of the new class?'
            )
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'Which type you want to create [form|entity|model|repository]?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $target = $input->getArgument('target');
        $type = $input->getArgument('type');

        if($type === 'form') {
            $formTypeGenerator = $this->getContainer()->get('enhavo_generator.generator.extend_form_type_generator');
            $formTypeGenerator->generate($source, $target);
        } else {
            throw new \InvalidArgumentException(sprintf('The type "%s" is not implement yet', $type));
        }

        $output->writeln('was created');
    }
}