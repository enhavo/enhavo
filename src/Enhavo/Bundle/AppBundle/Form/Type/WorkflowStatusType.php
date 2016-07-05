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

            $workflows = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findAll();
            foreach ($workflows as $wf) {
                $wfEntities = $wf->getEntity();
                if(in_array($type, $wfEntities)){
                    $workflow = $wf;
                    break;
                }
            }
            if($workflow != null) {
                if ($workflow->getActive() == true)
                {
                    $currentNode = null;
                    if ($data != null) {
                        $currentNode = $data->getNode();
                    } else {
                        $currentNode = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findOneBy(array(
                            'workflow' => $workflow,
                            'name' => 'creation'
                        ));
                    }

                    $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                        'nodeFrom' => $currentNode,
                    ));
                    $nodes = array();
                    foreach ($transitions as $transition) {
                        if($this->securityContext->isGranted('WORKFLOW_TRANSITION', $transition))
                        {
                            $nodes[] = $transition->getNodeTo();
                        }
                    }

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
                            /*'translationDomain' => 'EnhavoWorkflowBundle',*/
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