<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.03.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

use Symfony\Component\Form\FormInterface;

interface ResolverInterface
{
    /**
     * Return all items from this group
     *
     * @param $group
     * @return ItemInterface[]
     */
    public function resolveItemGroup($group);

    /**
     * Return all default items
     *
     * @return ItemInterface[]
     */
    public function resolveItems();

    /**
     * Get item by name
     *
     * @param $name
     * @return ItemInterface
     */
    public function resolveItem($name);

    /**
     * Get factory
     *
     * @param string $name
     * @return FactoryInterface
     */
    public function resolveFactory($name);

    /**
     * Get form type
     *
     * @param string $name
     * @return FormInterface
     */
    public function resolveFormType($name);

    /**
     * Get template path for rendering the form
     *
     * @param string $name
     * @return string
     */
    public function resolveFormTemplate($name);
}