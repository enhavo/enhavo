<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\AppBundle\Command;


use Enhavo\Bundle\AppBundle\Template\TemplateResolverInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugTemplateCommand extends Command
{
    public function __construct(
        private readonly TemplateResolverInterface $templateResolver,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:template')
            ->setDescription('Show resolved template')
            ->addArgument('template', InputArgument::REQUIRED, 'To resolved template')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $templateName = $input->getArgument('template');
        $template = $this->templateResolver->resolve($templateName);

        $output->writeln(sprintf('Template path: "<info>%s</info>"', $templateName));
        $output->writeln(sprintf('Resolved path: "<info>%s</info>"', $template));

        return Command::SUCCESS;
    }
}
