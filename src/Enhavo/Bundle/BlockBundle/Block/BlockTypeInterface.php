<?php

/**
 * BlockInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\ConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface BlockTypeInterface extends ConfigurationInterface
{
    public function createViewData(BlockInterface $block, $resource, array $options);

    public function finishViewData(BlockInterface $block, array $viewData, $resource, array $options);

    public function getModel(array $options);

    public function getForm(array $options);

    public function getParent(array $options);

    public function getFactory(array $options);

    public function getTemplate(array $options);

    public function getFormTemplate(array $options);

    public function getGroups(array $options);

    public function configureOptions(OptionsResolver $resolver);
}
