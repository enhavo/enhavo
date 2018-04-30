<?php
/**
 * ItemType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\DynamicForm\ItemResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicItemType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', HiddenType::class);

        $resolver = $this->getResolver($options);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($resolver) {
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && isset($item['type'])) {
                $builder = $resolver->resolveFormBuilder($item['type']);
                foreach($builder as $child) {
                    $form->add($child);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($resolver) {
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && $item->getType()) {
                $builder = $resolver->resolveFormBuilder($item->getType());
                foreach($builder as $child) {
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

        $data = $form->getData();
        if($data instanceof Item) {
            $view->vars['label'] = $resolver->getItem($data->getType())->getLabel();
        }

        if(isset($options['item_full_name'])) {
            $view->vars['full_name'] = sprintf('%s', ($options['item_full_name']));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'item_resolver' => null,
            'csrf_protection' => false,
            'item_full_name' => null
        ]);
    }

    public function getName()
    {
        return 'enhavo_app_dynamic_item';
    }

    /**
     * @param array $options
     * @return ItemResolverInterface
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

        if(!$resolver instanceof ItemResolverInterface) {
            throw new \Exception(sprintf('Resolver for dynamic form is not implements ItemResolverInterface', $resolver));
        }

        return $resolver;
    }
} 