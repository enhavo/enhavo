<?php
/**
 * CategorySubscriber.php
 *
 * @since 17/10/15
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\CategoryBundle\EventListener;

use Enhavo\Bundle\CategoryBundle\Entity\Collection;
use Enhavo\Bundle\CategoryBundle\Model\CollectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Component\Resource\Event\ResourceEvent;
use Enhavo\Bundle\CategoryBundle\Entity\Category;

class CategorySubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_category.category.pre_update' => array('onPreCreate', 0),
            'enhavo_category.category.pre_create' => array('onPreUpdate', 0),
        );
    }

    public function onPreCreate(ResourceEvent $event)
    {
        /** @var $category Category */
        $category = $event->getSubject();
        $this->setCollection($category);
    }

    public function onPreUpdate(ResourceEvent $event)
    {
        /** @var $category Category */
        $category = $event->getSubject();
        $this->setCollection($category);
    }

    protected function setCollection(Category $category)
    {
        if($category->getCollection() instanceof CollectionInterface) {
            return; //do nothing if collection is already set
        }

        $collectionName = $this->container->getParameter('enhavo_category.default_collection');
        $collection = $this->container->get('enhavo_category.repository.collection')->findOneBy([
            'name' => $collectionName
        ]);

        if($collection === null) {
            $collection = new Collection();
            $collection->setName($collectionName);
            $this->container->get('doctrine.orm.entity_manager')->persist($collection);
        }

        $category->setCollection($collection);
    }
}