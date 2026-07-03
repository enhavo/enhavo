<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'translation:auto-translate',
    description: 'Auto translate entity',
)]
class AutoTranslateCommand extends Command
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly EntityManagerInterface $em,
        private readonly TranslationManager $translationManager,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'FQCN or resource name')
            ->addArgument('id', InputArgument::REQUIRED, 'id of the entity')
            ->addArgument('locale', InputArgument::REQUIRED, 'locale')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityName = $input->getArgument('entity');
        $id = $input->getArgument('id');
        $locale = $input->getArgument('locale');

        $entity = null;
        if (class_exists($entityName)) {
            $repository = $this->em->getRepository($entity);
            $entity = $repository->find($id);
        } elseif ($this->resourceManager->getMetadata($entityName)) {
            $repository = $this->resourceManager->getRepository($entityName);
            $entity = $repository->find($id);
        } else {
            $output->writeln('<error>Entity "'.$entityName.'" is not a FCQN nor a valid resource name</error>');
            return Command::FAILURE;
        }

        if ($entity === null) {
            $output->writeln('<error>Entity with id "'.$id.'" not found</error>');
            return Command::FAILURE;
        }

        if (null === $entity) {
            $output->writeln('Entity not found');
            return Command::FAILURE;
        }

        $this->translationManager->applyAutoTranslation($entity, $locale);
        $this->resourceManager->save($entity);

        return Command::SUCCESS;
    }
}
