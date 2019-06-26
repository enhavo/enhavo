<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 17:24
 */

namespace Enhavo\Bundle\TemplateBundle\Block;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\TemplateBundle\Entity\ResourceBlock;
use Enhavo\Bundle\TemplateBundle\Factory\ResourceBlockFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\TemplateBundle\Form\Type\ResourceBlockType as ResourceBlockFormType;

class ResourceBlockType extends AbstractBlockType
{
    public function createViewData(BlockInterface $block, $resource, array $options)
    {
        $data = parent::createViewData($block, $resource, $options);
        /** @var ResourceBlock $block */
        $data['template'] = $block->getTemplate();
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => ResourceBlock::class,
            'parent' => ResourceBlock::class,
            'form' => ResourceBlockFormType::class,
            'factory' => ResourceBlockFactory::class,
            'repository' => 'EnhavoBlockBundle:CiteText',
            'template' => 'EnhavoTemplateBundle:Theme/Block:resource.html.twig',
            'form_template' => 'EnhavoBlockBundle:Form:block_empty.html.twig',
            'label' =>  'resource.label.resource',
            'translationDomain' => 'EnhavoTemplateBundle',
            'groups' => ['template']
        ]);
    }

    public function getType()
    {
        return 'resource';
    }
}
