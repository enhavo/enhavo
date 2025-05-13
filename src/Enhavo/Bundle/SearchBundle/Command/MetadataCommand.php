<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

class MetadataCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('enhavo:search:metadata')
            ->setDescription('Checks the metadata')
            ->addArgument('yamlPath', InputArgument::REQUIRED, 'Path to the search.yaml file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yamlPath = $input->getArgument('yamlPath');

        if (!file_exists($yamlPath)) {
            $output->writeln('The specified YAML file does not exist.');

            return Command::FAILURE;
        }

        $yamlContent = Yaml::parseFile($yamlPath);

        if (!isset($yamlContent['enhavo_search']['metadata'])) {
            $output->writeln('The "metadata" key does not exist in the YAML file.');

            return Command::FAILURE;
        }

        $metadata = $yamlContent['enhavo_search']['metadata'];
        $accessor = PropertyAccess::createPropertyAccessor();
        $invalidClasses = [];

        $output->writeln('Checking file...');
        foreach ($metadata as $className => $properties) {
            if (!class_exists($className)) {
                $invalidClasses[] = sprintf('Class "%s" does not exist in this project.', $className);
                continue;
            }

            $class = new \ReflectionClass($className);
            $entityProperties = $class->getProperties(\ReflectionProperty::IS_PRIVATE);

            foreach ($properties['properties'] as $property => $config) {
                $propertyExists = false;

                foreach ($entityProperties as $entityProperty) {
                    if ($entityProperty->getName() === $property) {
                        $propertyExists = true;
                        break;
                    }
                }

                if (!$propertyExists) {
                    $invalidClasses[] = sprintf('Property "%s" does not exist in class "%s".', $property, $className);
                }
            }
        }

        if (empty($invalidClasses)) {
            $output->writeln('Everything looks good.');

            return Command::SUCCESS;
        }
        $output->writeln('The following items do not exist in this project:');
        foreach ($invalidClasses as $invalidItem) {
            $output->writeln('- '.$invalidItem);
        }

        return Command::FAILURE;
    }
}
