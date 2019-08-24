<?php
/**
 * BlockType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
use Enhavo\Bundle\FormBundle\Form\Type\DynamicItemType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class NodeType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', PositionType::class);
        $builder->add('name', HiddenType::class);

        $itemProperty = $options['item_property'];
        $resolver = $this->getResolver($options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($resolver, $itemProperty) {
            $item = $event->getData();
            $form = $event->getForm();
            $accessor = PropertyAccess::createPropertyAccessor();
            if(!empty($item) && $accessor->getValue($item, $itemProperty)) {
                $name = $accessor->getValue($item, $itemProperty);
                /** @var Block $block */
                $block = $resolver->resolveItem($name);
                $template = $block->getTemplate();
                if(is_array($template)) {
                    $choices = [];
                    foreach($template as $key => $value) {
                        $choices[$value['label']] = $key;
                    }
                    $form->add('template', ChoiceType::class, [
                        'label' => 'Template',
                        'choices' => $choices
                    ]);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($resolver, $itemProperty) {
            $item = $event->getData();
            $form = $event->getForm();
            if(!empty($item) && isset($item[$itemProperty])) {
                $name = $item[$itemProperty];
                /** @var Block $block */
                $block = $resolver->resolveItem($name);
                $template = $block->getTemplate();
                if(is_array($template)) {
                    $choices = [];
                    foreach($template as $key => $value) {
                        $choices[$value['label']] = $key;
                    }
                    $form->add('template', ChoiceType::class, [
                        'label' => 'Template',
                        'choices' => $choices
                    ]);
                }
            }
        });

        $builder->add('block', $options['item_type_form'], $options['item_type_parameters']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Node::class,
            'item_type_form' => null,
            'item_type_parameters' => [],
            'item_property' => 'name'
        ));
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

    public function getParent()
    {
        return DynamicItemType::class;
    }
} 
