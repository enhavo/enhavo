<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-19
 */

namespace Enhavo\Bundle\ThemeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WebpackBuildCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('enhavo:theme:webpack:build')
            ->setDescription('Build webpack');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Build webpack ...');

        $process = new Process(['yarn', 'encore', 'dev'], $this->container->getParameter('kernel.project_dir'));
        $process->setTimeout(600);

        $process->start();

        foreach ($process as $type => $data) {
            $output->writeln($data);
        }

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return Command::SUCCESS;
    }
}
