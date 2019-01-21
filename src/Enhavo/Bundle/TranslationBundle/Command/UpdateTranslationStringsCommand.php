<?php

namespace Enhavo\Bundle\TranslationBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationString;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTranslationStringsCommand extends Command
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var array
     */
    private $translationStrings;

    /**
     * UpdateTranslationStringsCommand constructor.
     * @param FactoryInterface $factory
     * @param RepositoryInterface $repository
     * @param EntityManagerInterface $em
     * @param array $translationStrings
     */
    public function __construct(
        FactoryInterface $factory,
        RepositoryInterface $repository,
        EntityManagerInterface $em,
        array $translationStrings
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->em = $em;
        $this->translationStrings = $translationStrings;
        parent::__construct();
    }


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
        $alreadyInDatabase = 0;
        $addedToDatabase = 0;

        foreach($this->translationStrings as $translationString) {
            $exists = $this->repository->findBy(array(
                'translationKey' => $translationString
            ));
            if ($exists) {
                $alreadyInDatabase++;
            } else {
                /** @var TranslationString $newEntity */
                $newEntity = $this->factory->createNew();
                $newEntity->setTranslationKey($translationString);
                $newEntity->setTranslationValue('');
                $this->em->persist($newEntity);
                $addedToDatabase++;
            }
        }
        if ($addedToDatabase > 0) {
            $this->em->flush();
        }
        if (count($this->translationStrings) == 0) {
            $output->writeln('No translation strings defined in configuration files.');
        } else {
            $output->writeln($addedToDatabase . ' new translation strings added to database, ' . $alreadyInDatabase . ' already set.');
        }
    }
}
