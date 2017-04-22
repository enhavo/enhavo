<?php
/**
 * RouteType.php
 *
 * @since 19/05/15
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Validator\Constraints\Route as RouteConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'label.url',
            'translation_domain' => 'EnhavoAppBundle',
            'data_class' => Route::class,
            'constraints' => array(
                new RouteConstraint,
            )
        ));
    }

    public function getName()
    {
        return 'enhavo_route';
    }
}