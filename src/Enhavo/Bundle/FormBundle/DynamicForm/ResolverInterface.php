<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.03.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\FormBundle\DynamicForm;

use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Symfony\Component\Form\FormInterface;

interface ResolverInterface
{
    /**
     * Return all items from this group
     *
     * @param $group
     * @return ItemInterface[]
     * @throws ResolverException
     */
    public function resolveItemGroup($group);

    /**
     * Return all default items
     *
     * @return ItemInterface[]
     * @throws ResolverException
     */
    public function resolveDefaultItems();

    /**
     * Get item by name
     *
     * @param $name
     * @return ItemInterface
     * @throws ResolverException
     */
    public function resolveItem($name);

    /**
     * Get factory
     *
     * @param string $name
     * @return FactoryInterface
     * @throws ResolverException
     */
    public function resolveFactory($name);

    /**
     * Get form type
     *
     * @param string $name
     * @param object|null $data
     * @param array $options
     * @return FormInterface
     * @throws ResolverException
     */
    public function resolveForm($name, $data = null, $options = []);

    /**
     * Get template path for rendering the form
     *
     * @param string $name
     * @return string
     * @throws ResolverException
     */
    public function resolveFormTemplate($name);
}
