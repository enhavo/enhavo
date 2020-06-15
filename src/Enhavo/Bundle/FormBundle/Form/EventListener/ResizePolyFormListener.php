<?php

namespace Enhavo\Bundle\FormBundle\Form\EventListener;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * ResizePolyFormListener
 * A Form Resize listener capable of coping with a PolyCollectionType.
 * Copied and customize from InfiniteFormBundle. Thanks for the assume work.
 *
 * @author gseidel
 */
class ResizePolyFormListener extends ResizeFormListener
{
    /** @var array Stores an array of Types with the Type name as the key. */
    private $typeMap = array();

    /** @var array Stores an array of types with the Data Class as the key. */
    private $classMap = array();

    /** @var string Name of the hidden field identifying the type. */
    private $typeFieldName;

    /** @var null|string Name of the index field on the given entity. */
    private $indexProperty;

    /** @var \Symfony\Component\PropertyAccess\PropertyAccessor */
    private $propertyAccessor;

    /** @var \Closure|null */
    private $entryTypeResolver;

    /**
     * @param FormInterface[] $prototypes
     * @param array $options
     * @param bool $allowAdd
     * @param bool $allowDelete
     * @param string $typeFieldName
     * @param string $indexProperty
     */
    public function __construct(array $prototypes, array $options = array(), $allowAdd = false, $allowDelete = false, $typeFieldName = '_key', $indexProperty = null, $entryTypeResolver = null)
    {
        $this->typeFieldName = $typeFieldName;
        $this->indexProperty = $indexProperty;
        $this->entryTypeResolver = $entryTypeResolver;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $defaultType = null;
        foreach ($prototypes as $key => $prototype) {
            /** @var FormInterface $prototype */
            $modelClass = $prototype->getConfig()->getOption('model_class');
            $type = $prototype->getConfig()->getType()->getInnerType();

            if (null === $defaultType) {
                $defaultType = $type;
            }

            $this->typeMap[$key] = get_class($type);
            $this->classMap[$modelClass] = $key;
        }

        parent::__construct(get_class($defaultType), $options, $allowAdd, $allowDelete);
    }

    /**
     * @param object $entryData
     * @return string
     */
    private function resolveEntryKey($entryData)
    {
        if($this->entryTypeResolver !== null) {
            return call_user_func($this->entryTypeResolver, $entryData);
        } elseif(is_object($entryData)) {
            $class = get_class($entryData);
            $class = ClassUtils::getRealClass($class);

            if (array_key_exists($class, $this->classMap)) {
                return $this->classMap[$class];
            }

            throw new \InvalidArgumentException();
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Checks the form data for a hidden _type field that indicates
     * the form type to use to process the data.
     *
     * @param array $data
     *
     * @return string|FormTypeInterface
     *
     * @throws \InvalidArgumentException when _type is not present or is invalid
     */
    private function getKeyForData(array $data)
    {
        if (!array_key_exists($this->typeFieldName, $data) || !array_key_exists($data[$this->typeFieldName], $this->typeMap)) {
            throw new \InvalidArgumentException('Unable to determine the Type for given data');
        }

        return $data[$this->typeFieldName];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Then add all rows again in the correct order for the incoming data
        foreach ($data as $name => $value) {
            $key = $this->resolveEntryKey($value);

            $form->add($name, $this->typeMap[$key], array_replace(array(
                'property_path' => '['.$name.']',
            ), isset($this->options[$key]) ? $this->options[$key] : []));
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data || '' === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // Process entries by IndexProperty
        if (!is_null($this->indexProperty)) {
            // Reindex the submit data by given index
            $indexedData = array();
            $unindexedData = array();
            $finalData = array();
            foreach ($data as $item) {
                if (isset($item[$this->indexProperty])) {
                    $indexedData[$item[$this->indexProperty]] = $item;
                } else {
                    $unindexedData[] = $item;
                }
            }

            // Add all additional rows to the end of the array
            $name = $form->count();
            foreach ($unindexedData as $item) {
                if ($this->allowAdd) {
                    $key = $this->getKeyForData($item);
                    $form->add($name, $this->typeMap[$key], array_replace(array(
                        'property_path' => '['.$name.']',
                    ), isset($this->options[$key]) ? $this->options[$key] : []));
                }

                // Add to final data array
                $finalData[$name] = $item;
                ++$name;
            }

            // Remove all empty rows
            if ($this->allowDelete) {
                foreach ($form as $name => $child) {
                    // New items will have null data. Skip these.
                    if (!is_null($child->getData())) {
                        $index = $this->propertyAccessor->getValue($child->getData(), $this->indexProperty);
                        if (!isset($indexedData[$index])) {
                            $form->remove($name);
                        } else {
                            $finalData[$name] = $indexedData[$index];
                        }
                    }
                }
            }

            // Replace submitted data with new form order
            $event->setData($finalData);
        } else {
            // Remove all empty rows
            if ($this->allowDelete) {
                foreach ($form as $name => $child) {
                    if (!isset($data[$name])) {
                        $form->remove($name);
                    }
                }
            }

            // Add all additional rows
            if ($this->allowAdd) {
                foreach ($data as $name => $value) {
                    if (!$form->has($name)) {
                        $key = $this->getKeyForData($value);
                        $form->add($name, $this->typeMap[$key], array_replace(array(
                            'property_path' => '['.$name.']',
                        ), isset($this->options[$key]) ? $this->options[$key] : []));
                    }
                }
            }
        }
    }
}
