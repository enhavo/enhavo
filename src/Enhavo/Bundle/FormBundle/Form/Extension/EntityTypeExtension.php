<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.01.18
 * Time: 14:54
 */

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['multiple'] && $options['sortable']) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $submittedData = null;
            $builder->addViewTransformer(new CallbackTransformer(
                function ($originalDescription) {
                    return $originalDescription;
                },
                function ($submittedDescription) use (&$submittedData) {
                    $submittedData = $submittedDescription;
                    return $submittedDescription;
                }
            ));

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use (&$submittedData, $propertyAccessor, $options) {
                $data = $event->getForm()->getData();
                if($data instanceof Collection) {
                    foreach($data as $entry) {
                        $id = $propertyAccessor->getValue($entry, 'id');
                        $position = array_search($id, $submittedData);
                        $position++;
                        $propertyAccessor->setValue($entry, $options['sort_property'], $position);
                    }
                }
                return $data;
            });

        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($options['multiple'] && $options['sortable']) {
            $view->vars['attr']['data-select2-options'] = json_encode([
                'multiple' => $options['multiple'],
                'sortable' => $options['sortable'],
                'count' => $options['count']
            ]);
        }
        $view->vars['count'] = $options['count'];
        $view->vars['multiple'] = $options['multiple'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'list' => false,
            'sortable' => false,
            'sort_property' => null,
            'count' => true,
        ]);
    }

    public function getExtendedType()
    {
        return EntityType::class;
    }
}
