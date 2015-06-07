<?php
/**
 * RouteType.php
 *
 * @since 19/05/15
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Form\Type;

use esperanto\AdminBundle\Entity\Route;
use esperanto\AdminBundle\Validator\Constraints\Route as RouteConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RouteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('staticPrefix', 'text');

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if($data instanceof Route) {
                if($form->getParent() && $form->getParent()->getData()) {
                    $dataClass = $form->getParent()->getData();
                    $data->setContent($dataClass);
                }
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'label.url',
            'data_class' => 'esperanto\AdminBundle\Entity\Route',
            'constraints' => array(
                new RouteConstraint,
            )
        ));
    }

    public function getName()
    {
        return 'esperanto_route';
    }
}