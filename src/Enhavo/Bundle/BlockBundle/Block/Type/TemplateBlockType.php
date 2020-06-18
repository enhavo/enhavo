<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 13:12
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Factory\TemplateBlockFactory;
use Enhavo\Bundle\BlockBundle\Model\Block\TemplateBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\BlockBundle\Form\Type\TemplateBlockType as TemplateBlockFormType;

class TemplateBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => TemplateBlock::class,
            'form' => TemplateBlockFormType::class,
            'factory' => TemplateBlockFactory::class,
            'template' => 'theme/block/template.html.twig',
            'label' => 'template.label.template',
            'translation_domain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public static function getName(): ?string
    {
        return 'template';
    }
}
