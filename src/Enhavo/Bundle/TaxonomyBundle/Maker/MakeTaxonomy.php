<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeTaxonomy extends AbstractMaker
{
    /**
     * @var MakerUtil
     */
    private $util;

    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct(MakerUtil $util)
    {
        $this->util = $util;
        $this->nameTransformer = new NameTransformer();
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new taxonomy')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your taxonomy?')
            ->addArgument('hierarchy', InputArgument::REQUIRED, 'Is the taxonomy hierarchy? [yes/no]')
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
        $bundleName = trim($input->getArgument('bundle'));
        $name = trim($input->getArgument('name'));
        $path = $this->util->getBundlePath($bundleName);
        $hierarchy = $input->getArgument('hierarchy');
        $namespace = sprintf('%s\\Form\\Type', $this->util->getBundleNamespace($bundleName));
        $className = sprintf('%sType', $name);
        $class = sprintf('%s\\%s', $namespace, $className);

        $generator->generateFile(
            sprintf('%sResources/config/routing/admin/%s.yaml', $path, $this->nameTransformer->snakeCase($name)),
            $this->util->getRealpath('@EnhavoTaxonomyBundle/Resources/skeleton/routing.tpl.php'),
            [
                'bundle_name' => $this->nameTransformer->snakeCase($this->util->getBundleNameWithoutPostfix($bundleName)),
                'name' => $this->nameTransformer->snakeCase($name),
                'bundle_url' => $this->util->getBundleUrl($bundleName),
                'name_url' => $this->util->getResourceUrl($name),
                'hierarchy' => $hierarchy,
                'form_type' => $class,
            ]
        );

        $generator->generateClass(
            $class,
            $this->util->getRealpath('@EnhavoTaxonomyBundle/Resources/skeleton/TermType.tpl.php'),
            [
                'namespace' => $namespace,
                'class_name' => $className,
                'hierarchy' => $hierarchy,
                'name' => $this->nameTransformer->snakeCase($name),
            ]
        );

        $generator->writeChanges();

        $io->writeln(sprintf('Add the %s.yaml to your routing', $this->nameTransformer->snakeCase($name)));
    }
}
