<?php
/**
 * BlockType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
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

class DynamicBlockType extends AbstractType
{
    use ContainerAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $blockProperty = $options['block_property'];

        $builder->add($blockProperty, HiddenType::class);

        $resolver = $this->getResolver($options);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($resolver, $blockProperty) {
            $block = $event->getData();
            $form = $event->getForm();
            if(!empty($block) && isset($block[$blockProperty])) {
                $resolvedForm = $resolver->resolveForm($block[$blockProperty]);
                foreach($resolvedForm as $child) {
                    $form->add($child);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($resolver, $blockProperty) {
            $block = $event->getData();
            $form = $event->getForm();
            $accessor = PropertyAccess::createPropertyAccessor();
            if(!empty($block) && $accessor->getValue($block, $blockProperty)) {
                $resolvedForm = $resolver->resolveForm($accessor->getValue($block, $blockProperty));
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
        $blockProperty = $options['block_property'];

        $accessor = PropertyAccess::createPropertyAccessor();

        $data = $form->getData();
        if($data) {
            $block = $resolver->resolveBlock($accessor->getValue($data, $blockProperty));
            $view->vars['label'] = $block->getLabel();
            $view->vars['translation_domain'] = $block->getTranslationDomain();
        }

        if(isset($options['block_full_name'])) {
            $view->vars['full_name'] = sprintf('%s', ($options['block_full_name']));
        }

        $view->vars['block_template'] = $resolver->resolveFormTemplate($accessor->getValue($data, $blockProperty));;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'block_property' => 'type',
            'block_resolver' => null,
            'block_full_name' => null
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_dynamic_block';
    }

    /**
     * @param array $options
     * @return ResolverInterface
     * @throws \Exception
     */
    private function getResolver(array $options)
    {
        $resolver = $options['block_resolver'];
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