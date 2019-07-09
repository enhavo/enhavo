<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 19:02
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractBlockType extends AbstractType implements BlockTypeInterface
{
    public function createViewData(BlockInterface $block, $resource, array $options)
    {
        return [
            'block' => $block,
            'resource' => $resource
        ];
    }

    public function finishViewData(BlockInterface $block, array $viewData, $resource, array $options)
    {
        return $viewData;
    }

    public function getModel(array $options)
    {
        return $options['model'];
    }

    public function getForm(array $options)
    {
        return $options['form'];
    }

    public function getRepository(array $options)
    {
        return $options['repository'];
    }

    public function getLabel(array $options)
    {
        return $options['label'];
    }

    public function getTranslationDomain(array $options)
    {
        return $options['translationDomain'];
    }

    public function getParent(array $options)
    {
        return $options['parent'];
    }

    public function getFactory(array $options)
    {
        return $options['factory'];
    }

    public function getTemplate(array $options)
    {
        return $options['template'];
    }

    public function getFormTemplate(array $options)
    {
        return $options['form_template'];
    }

    public function getGroups(array $options)
    {
        return $options['groups'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'options' => [],
            'parent' => null,
            'translationDomain' => null,
            'form_template' => '@EnhavoBlock/admin/form/block/block.html.twig',
            'groups' => ['default']
        ]);

        $resolver->setRequired([
            'label',
            'factory',
            'model',
            'template',
            'form',
            'repository',
        ]);
    }
}
