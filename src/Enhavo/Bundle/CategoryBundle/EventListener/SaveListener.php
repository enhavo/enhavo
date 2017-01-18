<?php

namespace Enhavo\Bundle\CategoryBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Deletes everything belonging to the workflow
 */
class SaveListener
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $categoryCollectionClass;

    public function __construct(EntityManager $em, $categoryCollectionClass)
    {
        $this->em = $em;
        $this->categoryCollectionClass = $categoryCollectionClass;
    }

    public function onSave(GenericEvent $event)
    {
        if(get_class($event->getSubject()) == $this->categoryCollectionClass){
            $categoryRepository = $this->em->getRepository('EnhavoCategoryBundle:Category');
            $categories = $categoryRepository->findAll();
            foreach($categories as $category){
                if($category->getCollection() == null){
                    $this->em->remove($category);
                }
            }
            $this->em->flush();
        }
    }
}