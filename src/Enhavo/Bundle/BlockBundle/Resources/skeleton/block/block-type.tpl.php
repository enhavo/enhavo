<?= "<?php\n" ?>

namespace <?= $definition->getNamespace(); ?>\Block;

use <?= $definition->getEntityNamespace(); ?>\<?= $definition->getCamelName(); ?>;
use <?= $definition->getFactoryNamespace(); ?>\<?= $definition->getCamelName(); ?>Factory;
use <?= $definition->getFormNamespace(); ?>\<?= $definition->getCamelName(); ?>Type as <?= $definition->getCamelName(); ?>FormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class <?= $definition->getCamelName(); ?>Type extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => <?= $definition->getCamelName(); ?>::class,
            'form' => <?= $definition->getCamelName(); ?>FormType::class,
            'factory' => <?= $definition->getFactoryName(); ?>::class,
            'template' => '<?= $definition->getTemplateFileName(); ?>',
            'label' => '<?= $definition->getLabel(); ?>',
            'translationDomain' => <?= $definition->getTranslationDomain() ? "'".$definition->getTranslationDomain()."'" : 'null' ?>,
            'groups' => <?= $definition->getGroupsString() ? $definition->getGroupsString() : '[]' ?>
        ]);
    }

    public static function getName(): ?string
    {
        return '<?= $definition->getSnakeName(); ?>';
    }
}
