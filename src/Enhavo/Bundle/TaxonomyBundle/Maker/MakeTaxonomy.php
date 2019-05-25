<?php
/**
 * MakeTaxonomy.php
 *
 * @since 25/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\TaxonomyBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeTaxonomy extends AbstractMaker
{
    /**
     * @var MakerUtil
     */
    private $util;

    public function __construct(MakerUtil $util)
    {
        $this->util = $util;
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new taxonomy')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your taxonomy?')
            ->addArgument('hierarchy', InputArgument::OPTIONAL, 'Is the taxonomy hierarchy? [yes/no]', 'no')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:taxonomy';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $bundleName = $input->getArgument('bundle');
        $name = $input->getArgument('name');
        $path = $this->util->getBundlePath($bundleName);
        $hierarchy = $input->getArgument('hierarchy');

        $generator->generateFile(
            sprintf('%sResources/config/routing/admin/%s.yaml', $path, $this->util->camelCaseToSnakeCase($name)),
            $this->util->getRealpath('@EnhavoTaxonomyBundle/Resources/skeleton/routing.tpl.php'),
            [
                'bundle_name' => $this->util->camelCaseToSnakeCase($this->util->getBundleNameWithoutPostfix($bundleName)),
                'name' => $this->util->camelCaseToSnakeCase($name),
                'bundle_url' => $this->util->getBundleUrl($bundleName),
                'name_url' => $this->util->getResourceUrl($name),
                'hierarchy' => $hierarchy,
            ]
        );
        $generator->writeChanges();

        $io->writeln(sprintf('Add the %s.yaml to your routing', $this->util->camelCaseToSnakeCase($name)));
    }
}
