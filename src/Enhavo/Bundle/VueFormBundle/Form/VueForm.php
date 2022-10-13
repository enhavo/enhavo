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

        $data = $this->normalize($formView);
        $data['root'] = true;
        $data['method'] = $formView->vars['method'] ?? null;
        $data['action'] = $formView->vars['action'] ?? null;
        return $data;
    }

    private function normalize(FormView $formView): array
    {
        $array = $this->buildData($formView);
        $array['children'] = [];
        $array['root'] = false;

        foreach ($formView->children as $key => $child) {
            $array['children'][$key] = $this->normalize($child);
        }

        return $array;
    }

    private function buildData(FormView $formView): array
    {
        $vueData = new VueData();

        foreach ($this->classes as $class) {
            if ($class::supports($formView)) {
                /** @var VueTypeInterface $type */
                $type = $this->container->get($class);

                $type->buildView($formView, $vueData);
            }
        }

        return $vueData->toArray();
    }

    public function submit(array $data)
    {
        if ($data['compound']) {
            $returnData = [];
            foreach ($data['children'] as $key => $child) {
                $returnData[$child['name']] = $this->submit($child);
            }
            return $returnData;
        }

        return $data['value'];
    }
}
