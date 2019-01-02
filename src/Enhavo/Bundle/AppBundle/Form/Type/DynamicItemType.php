<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\DynamicForm\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DynamicItemType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $itemProperty = $options['item_property'];

        $builder->add($itemProperty, HiddenType::class);

        $resolver = $this->getResolver($options);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($resolver, $itemProperty) {
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && isset($item[$itemProperty])) {
                $resolvedForm = $resolver->resolveForm($item[$itemProperty]);
                foreach($resolvedForm as $child) {
                    $form->add($child);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($resolver, $itemProperty) {
            $item = $event->getData();
            $form = $event->getForm();
            $accessor = PropertyAccess::createPropertyAccessor();
            if(!empty($item) && $accessor->getValue($item, $itemProperty)) {
                $resolvedForm = $resolver->resolveForm($accessor->getValue($item, $itemProperty));
                foreach($resolvedForm as $child) {
                    $form->add($child);
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $resolver = $this->getResolver($options);
        $itemProperty = $options['item_property'];

        $accessor = PropertyAccess::createPropertyAccessor();

        $data = $form->getData();
        if($data) {
            $item = $resolver->resolveItem($accessor->getValue($data, $itemProperty));
            $view->vars['label'] = $item->getLabel();
            $view->vars['translation_domain'] = $item->getTranslationDomain();
        }

        if(isset($options['item_full_name'])) {
            $view->vars['full_name'] = sprintf('%s', ($options['item_full_name']));
        }

        $view->vars['item_template'] = $resolver->resolveFormTemplate($accessor->getValue($data, $itemProperty));;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'item_property' => 'type',
            'item_resolver' => null,
            'item_full_name' => null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_dynamic_item';
    }

    /**
     * @param array $options
     * @return ResolverInterface
     * @throws \Exception
     */
    private function getResolver(array $options)
    {
        $resolver = $options['item_resolver'];
        if(is_string($resolver)) {
            if(!$this->container->has($resolver)) {
                throw new \Exception(sprintf('Resolver "%s" for dynamic form not found', $resolver));
            }
            $resolver = $this->container->get($resolver);
        }

        if(!$resolver instanceof ResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form is not implements ResolverInterface', $resolver));
        }

        return $resolver;
    }
} 