<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Enhavo\Bundle\VueFormBundle\Exception\VueTypeException;
use Laminas\Stdlib\PriorityQueue;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormView;

class VueForm
{
    use ContainerAwareTrait;

    private PriorityQueue $classes;
    private bool $locked = false;

    public function __construct()
    {
        $this->classes = new PriorityQueue();
    }

    public function registerType(string $class, $priority = 100)
    {
        if ($this->locked) {
            throw VueTypeException::locked();
        }

        if (!in_array(VueTypeInterface::class, class_implements($class))) {
            throw VueTypeException::invalidInterface($class);
        }

        $this->classes->insert($class, $priority);
    }

    private function lock()
    {
        $this->locked = true;
    }

    public function createData(FormView $formView)
    {
        if (!$this->locked) {
            $this->lock();
        }

        $vueData = [];

        $data = $this->normalize($formView, $vueData);
        $data['root'] = true;
        $data['method'] = !empty($formView->vars['method']) ? $formView->vars['method'] : null;
        $data['action'] = !empty($formView->vars['action']) ? $formView->vars['action'] : null;

        $this->finishView($vueData);

        $array = $data->toArray();
        return $array;
    }

    private function finishView($vueData)
    {
        /** @var VueData $data */
        foreach ($vueData as $data) {
            foreach ($this->classes as $class) {
                if ($class::supports($data->getFormView())) {
                    /** @var VueTypeInterface $type */
                    $type = $this->container->get($class);
                    $type->finishView($data->getFormView(), $data);
                }
            }
        }
    }

    private function normalize(FormView $formView, array &$vueData): VueData
    {
        $data = $this->buildData($formView);
        $data['root'] = false;

        foreach ($formView->children as $key => $child) {
            $childVueData = $this->normalize($child, $vueData);
            $childVueData->setParent($data);
            $data->addChild($key, $childVueData);
        }

        $vueData[] = $data;
        return $data;
    }

    private function buildData(FormView $formView): VueData
    {
        $data = new VueData([], $formView);

        foreach ($this->classes as $class) {
            if ($class::supports($formView)) {
                /** @var VueTypeInterface $type */
                $type = $this->container->get($class);

                $type->buildView($formView, $data);
            }
        }

        return $data;
    }

    public function submit(array $data)
    {
        if ($data['compound']) {
            $returnData = [];
            foreach ($data['children'] as $child) {
                $returnData[$child['name']] = $this->submit($child);
            }
            return $returnData;
        }

        return $data['value'];
    }
}
