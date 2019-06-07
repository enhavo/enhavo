<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.03.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\FormBundle\DynamicForm;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

interface ResolverInterface
{
    /**
     * Return all blocks from this group
     *
     * @param $group
     * @return BlockInterface[]
     */
    public function resolveBlockGroup($group);

    /**
     * Return all default blocks
     *
     * @return BlockInterface[]
     */
    public function resolveDefaultBlocks();

    /**
     * Get block by name
     *
     * @param $name
     * @return BlockInterface
     */
    public function resolveBlock($name);

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
     * @param object|null $data
     * @param array $options
     * @return FormInterface
     */
    public function resolveForm($name, $data = null, $options = []);

    /**
     * Get template path for rendering the form
     *
     * @param string $name
     * @return string
     */
    public function resolveFormTemplate($name);
}