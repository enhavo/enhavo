<?php
/**
 * OneColumnConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Model\Column\OneColumnBlock;
use Enhavo\Bundle\BlockBundle\Factory\OneColumnBlockFactory;
use Enhavo\Bundle\BlockBundle\Form\Type\OneColumnBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => OneColumnBlock::class,
            'parent' => OneColumnBlock::class,
            'form' => OneColumnBlockType::class,
            'factory' => OneColumnBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:OneColumn',
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