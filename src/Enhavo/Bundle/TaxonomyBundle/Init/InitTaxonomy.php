<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-26
 * Time: 22:06
 */

namespace Enhavo\Bundle\TaxonomyBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\TaxonomyBundle\Entity\Taxonomy;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;

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
     * @param EntityManagerInterface $em
     * @param EntityRepository $repository
     * @param $configuration
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
        foreach($this->configuration as $key => $configuration) {
            $taxonomy = $this->repository->findOneBy([
                'name' => $key,
            ]);
            if($taxonomy === null) {
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
