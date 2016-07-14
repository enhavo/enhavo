<?php
/**
 * ItemTypeResolver.php
 *
 * @since 05/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item;

use Enhavo\Bundle\GridBundle\Exception\NoRenderSetFoundException;
use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\GridBundle\Exception\NoTypeFoundException;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ItemTypeResolver
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $sets;

    /**
     * @param $items
     * @param $container
     */
    public function __construct($items, $sets, $container)
    {
        $this->items = $items;
        $this->sets = $sets;
        $this->container = $container;
    }

    /**
     * @param $itemType ItemTypeInterface
     * @throws NoTypeFoundException
     * @return string
     */
    public function getType(ItemTypeInterface $itemType)
    {
        $className = get_class($itemType);
        foreach($this->items as $type => $item) {
            if($item['model'] == $className) {
                return $type;
            }
        }
        throw new NoTypeFoundException;
    }

    /**
     * @param $type
     * @throws NoTypeFoundException
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository($type)
    {
        $repository = $this->getDefinition($type, 'repository');
        return $this->solveRepository($repository);
    }

    /**
     * @param $repositoryName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function solveRepository($repositoryName)
    {
        if(preg_match('/:/', $repositoryName)) {
            return $this->container->get('doctrine')->getRepository($repositoryName);
        } else {
            return $this->container->get($repositoryName);
        }
    }

    /**
     * @param $type string
     * @return FormType
     * @throws NoTypeFoundException
     */
    public function getFormType($type)
    {
        $formType = $this->getDefinition($type, 'form');
        return $this->solveFormType($formType);
    }

    /**
     * @param $formType string
     * @return FormType
     */
    protected function solveFormType($formType)
    {
        if(preg_match('#\\\#', $formType)) {
            $form = new $formType;
        } else {
            $form = $this->container->get($formType);
        }
        return $form;
    }

    /**
     * @param $type string
     * @param $set string
     * @return string
     * @throws NoTypeFoundException
     * @throws NoRenderSetFoundException
     */
    public function getTemplate($type, $set = null)
    {
        if($set && is_array($this->sets)) {
            if(isset($this->sets[$set]) && isset($this->sets[$set][$type])) {
                return $this->sets[$set][$type];
            } else {
                throw new NoRenderSetFoundException(sprintf('Set [%s] or template for type [%s] not found', $set, $type));
            }
        }
        return $this->getDefinition($type, 'template');
    }

    /**
     * @param $type
     * @param $key
     * @return mixed
     * @throws NoTypeFoundException
     */
    protected function getDefinition($type, $key)
    {
        foreach($this->items as $typeName => $item) {
            if($typeName == $type) {
                if(isset($item[$key])) {
                    return $item[$key];
                } else {
                    return null;
                }
            }
        }
        throw new NoTypeFoundException(sprintf('Cant resolve type [%s], no type found with this name', $type));
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getLabel($type)
    {
        $label = $this->getDefinition($type, 'label');
        if($label === null) {
            return sprintf('label.%s', $type);
        }
        return $label;
    }
}