<?php

/**
 * BlockInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\Context;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface BlockTypeInterface
{
    public function createViewData(BlockInterface $block, array $options, $resource = null);

    public function finishViewData(BlockInterface $block, array $options, array $viewData, Context $context, $resource = null);

    public function getModel(array $options);

    public function getForm(array $options);

    public function getParent(array $options);

    public function getFactory(array $options);

    public function getTemplates(array $options);

    public function getFormTemplate(array $options);

    public function getGroups(array $options);

    public function configureOptions(OptionsResolver $resolver);
}
