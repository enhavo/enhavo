<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\AppBundle\Command;


use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Yaml\Yaml;

class DebugVueRoutesCommand extends Command
{
    public function __construct(
        private readonly VueRouteProviderInterface $provider,
        private readonly NormalizerInterface $normalizer,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:vue-routes')
            ->setDescription('Show vue routes')
            ->addArgument('group', InputArgument::OPTIONAL, 'group')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $group = $input->getArgument('group');
        $routes = $this->provider->getRoutes($group);

        $content = Yaml::dump($this->normalizer->normalize($routes));
        $output->writeln($content);

        return Command::SUCCESS;
    }
}
