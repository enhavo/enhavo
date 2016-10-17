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
     * @var ItemConfigurationCollection
     */
    protected $itemCollection;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $sets;

    /**
     * @param $itemCollection
     * @param $container
     */
    public function __construct(ItemConfigurationCollection $itemCollection, $sets, $container)
    {
        $this->itemCollection = $itemCollection;
        $this->sets = $sets;
        $this->container = $container;
    }

    /**
     * @param $itemType ItemTypeInterface|string
     * @throws NoTypeFoundException
     * @return string
     */
    public function getType($itemType)
    {
        if($itemType instanceof ItemTypeInterface) {
            $className = get_class($itemType);
        } else {
            $className = $itemType;
        }

        foreach($this->itemCollection->getItemConfigurations() as $item) {
            if($item->getModel() == $className) {
                return $item->getName();
            }
        }
        throw new NoTypeFoundException;
    }

    /**
     * @param $itemType ItemTypeInterface|string
     * @throws NoTypeFoundException
     *
     * @return string
     */
    public function getTypeByParent($itemType)
    {
        if($itemType instanceof ItemTypeInterface) {
            $className = get_class($itemType);
        } else {
            $className = $itemType;
        }

        foreach($this->itemCollection->getItemConfigurations() as $item) {
            if($item->getParent() == $className) {
                return $item->getName();
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
        $repository = $this->getDefinition($type)->getRepository();
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
        $formType = $this->getDefinition($type)->getForm();
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
                throw new NoRenderSetFoundException(sprintf('Set "%s" or template for type "%s" not found', $set, $type));
            }
        }
        return $this->getDefinition($type)->getTemplate();
    }

    /**
     * @param $type
     * @return ItemConfiguration
     * @throws NoTypeFoundException
     */
    public function getDefinition($type)
    {
        foreach($this->itemCollection->getItemConfigurations() as $item) {
            if($item->getName() == $type) {
                return $item;
            }
        }
        throw new NoTypeFoundException(sprintf('Cant resolve type "%s", no type found with this name', $type));
    }

    /**
     * @return ItemConfiguration[]
     */
    public function getItems()
    {
        return $this->itemCollection->getItemConfigurations();
    }

    public function getLabel($type)
    {
        $label = $this->getDefinition($type)->getLabel();
        if($label === null) {
            return sprintf('label.%s', $type);
        }
        return $label;
    }
}