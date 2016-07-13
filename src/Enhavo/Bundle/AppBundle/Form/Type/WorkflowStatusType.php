<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\WorkflowBundle\Entity\Transition;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\WorkflowBundle\Entity\Node;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;

/*
 * Shows the workflow section
 */
class WorkflowStatusType extends AbstractType
{
    protected $manager;
    protected $securityContext;
    protected $container;
    protected $hasTransitions;

    public function __construct(ObjectManager $manager, $securityContext, $container)
    {
        $this->manager = $manager;
        $this->securityContext = $securityContext;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $builder->getOptions()['attr'][0];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($type) {
            $form = $event->getForm();
            $data = $event->getData();

            //all workflows
            $workflows = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findAll();

            //get the right workflow to the given type --> check therefore every workflow if the entity array contains the type
            foreach ($workflows as $wf) {

                //get entities
                $wfEntities = $wf->getEntity();

                //check if type is in it
                if(in_array($type, $wfEntities)){

                    //if type is in --> this is our workflow
                    $workflow = $wf;
                    break;
                }
            }
            if($workflow != null) {

                //just show the workflowsection if the workflow is active
                if ($workflow->getActive() == true)
                {
                    //if there is no nodes yet, take the creation node of the worlflow
                    $currentNode = null;
                    if ($data != null) {
                        $currentNode = $data->getNode();
                    } else {
                        $currentNode = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findOneBy(array(
                            'workflow' => $workflow,
                            'name' => 'creation'
                        ));
                    }

                    //get transitions to node
                    $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                        'nodeFrom' => $currentNode,
                    ));

                    //check if current user is allowed to use these transitions
                    $nodes = array();
                    foreach ($transitions as $transition) {
                        if($this->securityContext->isGranted('WORKFLOW_TRANSITION', $transition))
                        {
                            $nodes[] = $transition->getNodeTo();
                        }
                    }

                    //set placeholder --> current workflowstatus
                    $placeholder = null;
                    if ($event->getData() != null) {
                        $placeholder = $event->getData()->getNode()->getName();
                    } else {
                        $placeholder = 'creation';
                    }

                    $this->hasTransitions = true;
                    if(!empty($nodes)){
                        $form->add('node', 'entity', array(
                            'label' => 'workflow.form.label.next_state',
                            'class' => 'EnhavoWorkflowBundle:Node',
                            'placeholder' => $placeholder,
                            'choice_label' => 'name',
                            'choices' => $nodes
                        ));
                    } else {
                        $this->hasTransitions = false;
                    }
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(is_object($view->vars['value'])) {
            $view->vars['workflow_status'] = $view->vars['value']->getNode()->getName();
        } else {
            $view->vars['workflow_status'] = 'creation';
        }

        if($this->hasTransitions == false){
            $translator = $this->container->get('translator');
            $view->vars['no_transitions'] = $translator->trans('workflow.label.noTransitions', array(), 'EnhavoWorkflowBundle');
        }
    }

    public function getName()
    {
        return 'enhavo_workflow_status';
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array(
            'data_class' => 'Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus'
        ));
    }
}