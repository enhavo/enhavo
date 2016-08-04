<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 12.05.16
 * Time: 10:25
 */

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * handles everything when you edit or save a workflow
 * this means updating workflowstatus of the belonging entities (for example when you removed a node and there is a workflowstatus that references this node)
 */
class WorkflowSaveListener {

    protected $em;

    protected $container;

    protected $workflowClass;

    public function __construct(EntityManager $em, $workflowClass, $container)
    {
        $this->em = $em;
        $this->workflowClass = $workflowClass;
        $this->container = $container;
    }

    public function onSave(GenericEvent $event)
    {
       if(get_class($event->getSubject()) == $this->workflowClass) { //if it is a 'save' of the workflow entity check the workflow-status of the belonging types and save the formNodes to the real nodes

            $workflow = $event->getSubject();

            //get the repository for the changed workflow
            $repositories = $this->getEntityRepository($workflow);

            if($repositories != null){

                //get all ressources without workflow-status
                $resources = array();
                foreach($repositories as $repository){
                    $currentResourcesEmpty = $repository->getEmptyWorkflowStatus();
                    //replace old workflowstatus with current workflowstatus
                    $this->removeOldWorkflowStatus($repository, $workflow);
                    $resources = array_merge($currentResourcesEmpty,$resources);
                }

                //check if there are some entries with workflow-status null
                if($resources != null) {
                    //set a workflow-status if the current workflow is active
                    if($workflow->getActive()){
                        $this->setWorkflowStatus($workflow, $resources);
                    }
                }
            }
            $this->writeNodes($workflow, $repositories);
            $this->em->flush();
        }
    }

    protected function writeNodes($workflow, $repositories)
    {
        //write displayed FormNodes into the real nodes
        $formNodes = $workflow->getFormNodes();
        $nodesCollection = $workflow->getNodes();
        $realNodes = array();
        foreach ($nodesCollection as $node) {
            $realNodes[] = $node;
        }

        //workflow transitions
        $transitions = $this->em->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
            'workflow' => $workflow
        ));

        //get all workflow-status in case a node got removed
        $allWFS = $this->em->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();

        //remove nodes (if some nodes have been removed from formNodes)
         foreach($realNodes as $realNode){
            if(!in_array($realNode,$formNodes) && !$realNode->getStart()){
                $workflow->removeNode($realNode);
                foreach($transitions as $transition){
                    if($transition->getNodeFrom() == $realNode || $transition->getNodeTo() == $realNode) {
                        $this->em->remove($transition);
                    }
                }
                //check if the removed node had a wfs
                foreach($allWFS as $wfs){
                    if($wfs->getNode() == $realNode){
                        foreach($repositories as $repository){
                            $currentResource = $repository->findOneBy(array(
                                'workflow_status' => $wfs
                            ));
                            if($currentResource != null){
                                $currentResource->setWorkflowStatus(null);
                                $this->em->remove($wfs);
                                break;
                            }
                        }

                    }
                }
                $this->em->remove($realNode);
            }
        }

        //create realNodes
        foreach ($formNodes as $key => $formNode) {
            if(!in_array($formNode,$realNodes) ){
                $workflow->addNode($formNode);
            }
        }
    }

    protected function setWorkflowStatus($workflow, $resources)
    {
        //get all nodes of the current workflow
        $nodes = $this->em->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
            'workflow' => $workflow
        ));

        //get start and end node
        $startNode = null;
        $endNode = null;
        foreach ($nodes as $node) {
            if($node->getStart()) {
                $startNode = $node;
            } else if($node->getEnd()) {
                $endNode = $node;
            }
        }

        foreach($resources as $element) {
            $workflowStatus = new WorkflowStatus();

            //check if there is a public field in the type
            if(method_exists($element, 'getPublic')){

                // if there is a public field, check if it is set true and set the end-node to the workflow-status; if it is false set the start-node to the workflow-status
                if($element->getPublic()){
                    $workflowStatus->setNode($endNode);
                } else {
                    $workflowStatus->setNode($startNode);
                }
            } else {

                //if there is no public field, just set all entries to the start-node
                $workflowStatus->setNode($startNode);
            }
            $element->setWorkflowStatus($workflowStatus);
        }
    }

    protected function getEntityRepository($workflow)
    {
        //compare the workflow entities with the entities in enhavo.yml and get the belonging repositories
        //which you can get from enhavo.yml
        if($workflow->getEntity() == null){
            return null;
        }

        //entities of enhavo.yml
        $possibleWFEntities = $this->container->getParameter('enhavo_workflow.entities');

        //get the repository-names the the workflow entities of the enhavo.yml
        $currentRepositories = array();
        foreach($possibleWFEntities as $possibleEntity) {
            foreach($workflow->getEntity() as $workflowEntity){
                if($possibleEntity['class'] == $workflowEntity){
                    $currentRepositories[] = $possibleEntity['repository'];
                }
            }
        }

        //get the repository-references
        $repository = array();
        foreach($currentRepositories as $currentRepository){
            if(strpos($currentRepository, ':')){
                $repository[] = $this->em->getRepository($currentRepository);
            } else {
                $repository[] = $this->container->get($currentRepository);
            }
        }
        return $repository;
    }

    protected function removeOldWorkflowStatus($repository, $newWorkflow){
        $resources = $repository->findAll();
        foreach ($resources as $resource) {
            $currentWFS = $resource->getWorkflowStatus();
            if($currentWFS != null){

                //check if workflow status is old
                $node = $currentWFS->getNode();
                $oldWorkflow = $node->getWorkflow();
                if($newWorkflow->getId() != $oldWorkflow->getId()){
                    //this workflow status is from an old workflow --> renew it
                    //check if it is public or not and set workflow status
                    $this->setWorkflowStatus($newWorkflow, array($resource));
                    $this->em->persist($resource);
                }
            }
        }
    }
}