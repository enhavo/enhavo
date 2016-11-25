<?php

namespace Enhavo\Bundle\CategoryBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;
/**
 * Deletes everything belonging to the workflow
 */
class SaveListener
{
    protected $em;
    protected $categoryCollectionClass;

    public function __construct(EntityManager $em, $categoryCollectionClass)
    {
        $this->em = $em;
        $this->categoryCollectionClass = $categoryCollectionClass;
    }

    //you need to delete all rows which belong to the workflow before you can delete the workflow itself (transitions->nodes->workflow-status)
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