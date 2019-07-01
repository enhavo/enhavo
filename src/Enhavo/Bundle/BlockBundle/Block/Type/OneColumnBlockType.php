<?php
/**
 * OneColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Type;

use Enhavo\Bundle\BlockBundle\Model\Column\OneColumnBlock;
use Enhavo\Bundle\BlockBundle\Factory\OneColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\OneColumnBlockType as OneColumnBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => OneColumnBlock::class,
            'parent' => OneColumnBlock::class,
            'form' => OneColumnBlockFormType::class,
            'factory' => OneColumnBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:OneColumnBlock',
            'template' => 'EnhavoBlockBundle:Block:one-column.html.twig',
            'form_template' => 'EnhavoBlockBundle:Form:block_fields.html.twig',
            'label' => 'one_column.label.one_column',
            'translationDomain' => 'EnhavoBlockBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'one_column';
    }
}
