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
    //protected $container;

    public function __construct(ObjectManager $manager/*, Container $container*/)
    {
        $this->manager = $manager;
        //$this->container = $container;
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

            $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                'entity' => $type,
            ));
           // $this->container->setParameter('workflow.'.$type, 'true');
            $currentNode = null;
            if($data != null) {
                $currentNode = $data->getNode();
            } else {
                $currentNode = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findOneBy(array(
                    'workflow' => $workflow,
                    'node_name' => 'creation'
                ));
            }

            $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                'node_from' => $currentNode,
            ));
            $nodes = array();
            foreach($transitions as $transition) {
                $nodes[] = $transition->getNodeTo();
            }

            $form->add('node', 'entity', array(
                'label' => 'workflow.next.nodes',
                /*'translationDomain' => 'EnhavoWorkflowBundle',*/
                'class' => 'EnhavoWorkflowBundle:Node',
                'placeholder' => '',
                'choice_label' => 'node_name',
                'choices' => $nodes
            ));
        });

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

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