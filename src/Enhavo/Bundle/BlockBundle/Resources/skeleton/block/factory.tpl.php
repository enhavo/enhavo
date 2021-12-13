<?= "<?php\n" ?>

namespace <?= $definition->getFactoryNamespace() ?>;

use Enhavo\Bundle\BlockBundle\Factory\AbstractBlockFactory;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use <?= $class->getNamespace() ?>\<?= $class->getName() ?>;

class <?= $definition->getFactoryName() ?> extends AbstractBlockFactory
{
   /**
    * @param BlockInterface|<?= $class->getName() ?> $original
    * @return <?= $class->getName() ?>

    */
    public function duplicate(BlockInterface $original)
    {
        $duplicate = $this->createNew();
        <?php foreach ($class->getProperties() as $property): ?>
$duplicate->set<?= ucfirst($property->getName()); ?>($original->get<?= ucfirst($property->getName()); ?>());
        <?php endforeach; ?>

        return $duplicate;
    }
}
