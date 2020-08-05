<?php


namespace Enhavo\Bundle\DashboardBundle\Widget;


use Enhavo\Bundle\DashboardBundle\Widget\Type\WidgetType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractWidgetType extends AbstractType implements WidgetTypeInterface
{
    /**
     * @var WidgetTypeInterface
     */
    protected $parent;

    /**
     * @inheritdoc
     */
    public function getPermission(array $options)
    {
        return $this->parent->getPermission($options);
    }

    /**
     * @inheritdoc
     */
    public function isHidden(array $options)
    {
        return $this->parent->isHidden($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public static function getParentType(): ?string
    {
        return WidgetType::class;
    }
}
