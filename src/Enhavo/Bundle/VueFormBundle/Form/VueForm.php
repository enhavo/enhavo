<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Enhavo\Bundle\VueFormBundle\Exception\VueTypeException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormView;

class VueForm
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

        $types = $class::getBlocks();

        foreach ($types as $key => $value) {
            if (is_string($key)) {
                $this->addToMap($key, $value, $class);
            } else {
                $this->addToMap($value, 100, $class);
            }
        }
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

    private function normalize(FormView $formView)
    {
        $array = $this->buildData($formView);
        $array['children'] = [];
        $array['root'] = false;

        foreach ($formView->children as $key => $child) {
            $array['children'][$key] = $this->normalize($child);
        }

        return $array;
    }

    private function buildData(FormView $formView)
    {
        $blockPrefixes = $formView->vars['block_prefixes'];

        $vueData = new VueData();

        foreach ($blockPrefixes as $block) {
            if (array_key_exists($block, $this->map)) {
                foreach ($this->map[$block] as $entry) {
                    $vueType = $this->getVueType($entry['vueType']);
                    $vueType->buildView($formView, $vueData);
                    if ($vueType->getComponent() !== null) {
                        $vueData['component'] = $vueType->getComponent();
                    }
                }
            }
        }

        if (isset($formView->vars['component'])) {
            $vueData['component'] = $formView->vars['component'];
        }

        return $vueData->toArray();
    }

    private function getVueType($class): VueTypeInterface
    {
        if (!array_key_exists($class, $this->types)) {
            $this->types[$class] = $this->container->get($class);
        }

        return $this->types[$class];
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
