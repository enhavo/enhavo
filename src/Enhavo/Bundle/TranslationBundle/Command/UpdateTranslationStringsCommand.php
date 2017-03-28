<?php

namespace Enhavo\Bundle\TranslationBundle\Command;

use Enhavo\Bundle\TranslationBundle\Entity\TranslationString;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTranslationStringsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('enhavo:translation:update')
            ->setDescription('Write missing translation string keys into database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $translationStringEntityFactory = $this->getContainer()->get('enhavo_translation.factory.translation_string');
        $translationStringEntityRepository = $this->getContainer()->get('enhavo_translation.repository.translation_string');
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $translationStrings = $this->getContainer()->getParameter('enhavo_translation.translation_strings');
        $alreadyInDatabase = 0;
        $addedToDatabase = 0;

        foreach($translationStrings as $translationString) {
            $exists = $translationStringEntityRepository->findBy(array(
                'translationKey' => $translationString
            ));
            if ($exists) {
                $alreadyInDatabase++;
            } else {
                /** @var TranslationString $newTranslationStringEntity */
                $newTranslationStringEntity = $translationStringEntityFactory->createNew();
                $newTranslationStringEntity->setTranslationKey($translationString);
                $newTranslationStringEntity->setTranslationValue('');
                $entityManager->persist($newTranslationStringEntity);
                $addedToDatabase++;
            }
        }
        if ($addedToDatabase > 0) {
            $entityManager->flush();
        }
        if (count($translationStrings) == 0) {
            $output->writeln('No translation strings defined in configuration files.');
        } else {
            $output->writeln($addedToDatabase . ' new translation strings added to database, ' . $alreadyInDatabase . ' already set.');
        }
    }
}
