<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;

class InitTaxonomy implements InitInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * InitTaxonomy constructor.
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repository, FactoryInterface $factory, $configuration)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->configuration = $configuration;
    }

    public function init(Output $io)
    {
        foreach ($this->configuration as $key => $configuration) {
            $taxonomy = $this->repository->findOneBy([
                'name' => $key,
            ]);
            if (null === $taxonomy) {
                $io->writeln(sprintf('Create "%s" taxonomy', $key));
                /** @var Taxonomy $taxonomy */
                $taxonomy = $this->factory->createNew();
                $taxonomy->setName($key);
                $this->em->persist($taxonomy);
            }
        }
        $this->em->flush();
    }

    public function getType()
    {
        return 'taxonomy';
    }
}
