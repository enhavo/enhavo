<?= "<?php\n" ?>

namespace <?= $namespace ?>\Block;

use <?= $entity_namespace ?>\<?= $name_camel ?>Block;
use <?= $factory_namespace ?>\<?= $name_camel ?>BlockFactory;
use <?= $form_namespace ?>\<?= $name_camel ?>BlockType as <?= $name_camel ?>BlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $name_camel ?>BlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => <?= $name_camel ?>Block::class,
            'parent' => <?= $name_camel ?>Block::class,
            'form' => <?= $name_camel ?>BlockFormType::class,
            'factory' => <?= $name_camel ?>BlockFactory::class,
            'repository' => '<?= $name_camel ?>Block::class',
            'template' => 'theme/block/<?= $name_kebab ?>.html.twig',
            'label' => '<?= $name_camel ?>',
            'translationDomain' => <?= $translation_domain ? "'".$translation_domain."'" : null ?>,
            'groups' => ['default', 'content']
        ]);
    }

    public function getType()
    {
        return '<?= $name_snake ?>';
    }
}
