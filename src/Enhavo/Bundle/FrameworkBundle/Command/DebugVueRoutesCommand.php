<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\Command;

use Enhavo\Bundle\FrameworkBundle\Vue\RouteProvider\VueRouteProviderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'debug:vue-routes',
    description: 'Show vue routes',
)]
class DebugVueRoutesCommand extends Command
{
    public function __construct(
        private readonly VueRouteProviderInterface $provider,
        private readonly NormalizerInterface $normalizer,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('group', InputArgument::OPTIONAL, 'group')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $group = $input->getArgument('group');
        $routes = $this->provider->getRoutes($group);

        $content = Yaml::dump($this->normalizer->normalize($routes));
        $output->writeln($content);

        return Command::SUCCESS;
    }
}
