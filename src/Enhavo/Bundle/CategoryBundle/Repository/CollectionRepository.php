<?php
/**
 * CollectionRepository.php
 *
 * @since 30/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;

class CollectionRepository extends EntityRepository
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function findCollectionOrCreate($name)
    {
        $collection =  $this->findOneBy(['name' => $name]);
        if($collection === null) {
            $collection = $this->createNew();
            $collection->setName($name);
        }
        return $collection;
    }

    private function createNew()
    {
        $collection = $this->factory->createNew();
        $this->getEntityManager()->persist($collection);
        return $collection;
    }
}