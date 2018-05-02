<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.03.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

use Symfony\Component\Form\FormBuilderInterface;

interface ItemResolverInterface
{
    /**
     * @param $group
     * @return ItemInterface[]
     */
    public function getItemGroup($group);

    /**
     * @param $name
     * @return ItemInterface
     */
    public function getItem($name);

    /**
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * @return mixed
     */
    public function getFormType($name);

    /**
     * @param $type
     * @return mixed
     */
    public function getFactory($type);

    /**
     * @param $type
     * @return FormBuilderInterface
     */
    public function resolveFormBuilder($type);

    /**
     * @param $type
     * @return FormBuilderInterface
     */
    public function resolveItemTemplate($type);
}