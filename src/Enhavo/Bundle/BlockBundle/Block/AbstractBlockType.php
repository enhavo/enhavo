<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 19:02
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Block\Type\BaseBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Component\Type\AbstractType ;

abstract class AbstractBlockType extends AbstractType implements BlockTypeInterface
{
    /** @var BlockTypeInterface */
    protected $parent;

    public function createViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
    {

    }

    public function finishViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
    {

    }

    public function getModel(array $options)
    {
        return $this->parent->getModel($options);
    }

    public function getForm(array $options)
    {
        return $this->parent->getForm($options);
    }

    public function getFactory(array $options)
    {
        return $this->parent->getFactory($options);
    }

    public function getTemplate(array $options)
    {
        return $this->parent->getTemplate($options);
    }

    public function getGroups(array $options)
    {
        return $this->parent->getGroups($options);
    }

    public function getLabel(array $options)
    {
        return $this->parent->getLabel($options);
    }

    public function getTranslationDomain(array $options)
    {
        return $this->parent->getTranslationDomain($options);
    }

    public static function getParentType(): ?string
    {
        return BaseBlockType::class;
    }
}
