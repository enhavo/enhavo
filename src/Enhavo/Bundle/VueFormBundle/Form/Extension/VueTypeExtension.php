<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Exception\VueTypeException;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueTypeExtension extends AbstractTypeExtension
{
    use ContainerAwareTrait;

    /** @var array */
    private $map = [];

    /** @var array */
    private $types = [];

    /** @var bool */
    private $locked = false;

    public function register($class)
    {
        if ($this->locked) {
            throw VueTypeException::locked();
        }

        if (!in_array(VueTypeInterface::class, class_implements($class))) {
            throw VueTypeException::invalidInterface($class);
        }

        $types = $class::getFormTypes();

        foreach ($types as $key => $value) {
            if (is_string($key)) {
                $this->addToMap($key, $value, $class);
            } else {
                $this->addToMap($value, 100, $class);
            }
        }
    }

    private function addToMap($formType, $priority, $vueType)
    {
        if (!array_key_exists($formType, $this->map)) {
            $this->map[$formType] = [];
        }

        $this->map[$formType][] = [
            'vueType' => $vueType,
            'priority' => $priority
        ];
    }

    private function lock()
    {
        $this->locked = true;
        foreach ($this->map as &$types) {
            usort($types, function ($one, $two) {
                return $two['priority'] - $one['priority'];
            });
        }
    }

    private function getVueType($class)
    {
        if (!array_key_exists($class, $this->types)) {
            $this->types[$class] = $this->container->get($class);
        }

        return $this->types[$class];
    }

    private function getTypes(FormInterface $form)
    {
        $types = [];
        $type = $form->getConfig()->getType();
        $types[] = get_class($type->getInnerType());

        while ($parent = $type->getParent()) {
            $types[] = get_class($parent->getInnerType());
            $type = $parent;
        }

        return array_reverse($types);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->locked) {
            $this->lock();
        }

        if (!array_key_exists('vue', $view->vars)) {
            $view->vars['vue'] = new VueData();
        }

        $types = $this->getTypes($form);

        foreach ($types as $type) {
            if (isset($this->map[$type])) {
                foreach ($this->map[$type] as $entry) {
                    $vueType = $this->getVueType($entry['vueType']);
                    if ($options['component'] === null) {
                        $view->vars['vue']['component'] = call_user_func_array([$vueType, 'getComponent'], [$options]);
                    }
                }
            }
        }

        foreach ($types as $type) {
            if (isset($this->map[$type])) {
                foreach ($this->map[$type] as $entry) {
                    $vueType = $this->getVueType($entry['vueType']);
                    call_user_func_array([$vueType, 'buildView'], [
                        $view,
                        $form,
                        $options,
                        $view->vars['vue']
                    ]);
                }
            }
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->locked) {
            $this->lock();
        }

        $types = $this->getTypes($form);
        foreach ($types as $type) {
            if (isset($this->map[$type])) {
                foreach ($this->map[$type] as $entry) {
                    $vueType = $this->getVueType($entry['vueType']);
                    call_user_func_array([$vueType, 'finishView'], [$view, $form, $options, $view->vars['vue']]);
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => null
        ]);
    }

    public function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
