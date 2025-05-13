<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\EventListener;

use Doctrine\Common\Util\ClassUtils;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * ResizePolyFormListener
 * A Form Resize listener capable of coping with a PolyCollectionType.
 * Copied and customize from InfiniteFormBundle. Thanks for the awesome work.
 *
 * @author gseidel
 */
class ResizePolyFormListener extends ResizeFormListener
{
    /** @var array Stores an array of Types with the Type name as the key. */
    private $typeMap = [];

    /** @var array Stores an array of types with the Data Class as the key. */
    private $classMap = [];

    /** @var string Name of the hidden field identifying the type. */
    private $typeFieldName;

    /** @var PropertyAccessor */
    private $propertyAccessor;

    /** @var \Closure|null */
    private $entryTypeResolver;

    /** @var PrototypeManager */
    private $prototypeManager;

    /** @var string */
    private $storageName;

    /** @var string[] */
    private $entryKeys;

    /** @var bool */
    private $loaded = false;

    /**
     * @param string   $storageName
     * @param string[] $entryKeys
     * @param bool     $allowAdd
     * @param bool     $allowDelete
     * @param string   $typeFieldName
     * @param \Closure $entryTypeResolver
     */
    public function __construct(
        PrototypeManager $prototypeManager,
        $storageName,
        $entryKeys = [],
        array $options = [],
        $allowAdd = false,
        $allowDelete = false,
        $typeFieldName = '_key',
        $entryTypeResolver = null,
    ) {
        $this->prototypeManager = $prototypeManager;
        $this->storageName = $storageName;
        $this->entryKeys = $entryKeys;
        $this->typeFieldName = $typeFieldName;
        $this->entryTypeResolver = $entryTypeResolver;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        parent::__construct('', $options, $allowAdd, $allowDelete);
    }

    private function loadMap()
    {
        if ($this->loaded) {
            return;
        }

        foreach ($this->prototypeManager->getPrototypes($this->storageName) as $prototype) {
            $modelClass = $prototype->getForm()->getConfig()->getOption('data_class');
            $type = $prototype->getForm()->getConfig()->getType()->getInnerType();

            $this->typeMap[$prototype->getParameters()['key']] = get_class($type);

            if (null !== $modelClass) {
                $this->classMap[$modelClass] = $prototype->getParameters()['key'];
            }
        }

        $this->loaded = false;
    }

    /**
     * @param object $entryData
     *
     * @return string
     */
    private function resolveEntryKey($entryData)
    {
        if (null !== $this->entryTypeResolver) {
            return call_user_func($this->entryTypeResolver, $entryData);
        } elseif (is_object($entryData)) {
            $class = get_class($entryData);
            $class = ClassUtils::getRealClass($class);

            if (array_key_exists($class, $this->classMap)) {
                return $this->classMap[$class];
            }

            throw new InvalidConfigurationException(sprintf('The class "%s" can\'t by mapped to a type. You can avoid this behavior be adding an entry_type_resolver', $class));
        }

        throw new InvalidConfigurationException(sprintf('The type "%s" is not supported by default. Add an entry_type_resolver to resolve this type', gettype($entryData)));
    }

    /**
     * Checks the form data for a hidden _type field that indicates
     * the form type to use to process the data.
     *
     * @throws InvalidArgumentException when _type is not present or is invalid
     *
     * @return string|FormTypeInterface
     */
    private function getKeyForData(array $data)
    {
        if (!array_key_exists($this->typeFieldName, $data) || !array_key_exists($data[$this->typeFieldName], $this->typeMap)) {
            throw new InvalidArgumentException('Unable to determine the Type for given data');
        }

        return $data[$this->typeFieldName];
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->loadMap();

        if (null === $data) {
            $data = [];
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

            $form->add($name, $this->typeMap[$key], array_replace([
                'property_path' => '['.$name.']',
            ], $this->options[$key] ?? []));
        }
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->loadMap();

        if (null === $data || '' === $data) {
            $data = [];
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

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
                    $form->add($name, $this->typeMap[$key], array_replace([
                        'property_path' => '['.$name.']',
                    ], $this->options[$key] ?? []));
                }
            }
        }
    }
}
