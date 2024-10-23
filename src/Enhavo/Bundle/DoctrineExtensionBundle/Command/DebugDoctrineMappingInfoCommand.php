<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugDoctrineMappingInfoCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:doctrine:mapping')
            ->addArgument('name', InputArgument::REQUIRED, 'FQCN of the entity')
            ->setDescription('Show mapping infos of an entity')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('name');

        $classMetadata = $this->em->getClassMetadata($className);

        $data = [
            'Name' => $classMetadata->name,
            'Inheritance Type' => [
                ClassMetadataInfo::INHERITANCE_TYPE_NONE => 'None',
                ClassMetadataInfo::INHERITANCE_TYPE_JOINED => 'Joined',
                ClassMetadataInfo::INHERITANCE_TYPE_SINGLE_TABLE => 'SingleTable',
                ClassMetadataInfo::INHERITANCE_TYPE_TABLE_PER_CLASS => 'TablePerClass',
            ][$classMetadata->inheritanceType],
        ];

        foreach ($data as $key => $value) {
            $output->writeln(sprintf('%s: <info>%s</info>', $key, $value));
        }

        return Command::SUCCESS;
    }
}
