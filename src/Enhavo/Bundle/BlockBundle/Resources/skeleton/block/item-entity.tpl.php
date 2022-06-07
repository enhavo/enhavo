<?= "<?php\n" ?>

namespace <?= $class->getNamespace(); ?>;

<?php foreach ($class->getUse() as $item) { ?>
use <?= $item; ?>;
<?php } ?>
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '<?= $orm->getTableName() ?>')]
class <?= $class->getName(); ?>

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

<?php foreach ($class->getProperties() as $property) { ?>
<?php $attributeType = $orm->getAttributeType($property->getName()); ?>
    #[ORM\<?= $attributeType ?>(
<?php foreach ($orm->getAttributeOptions($property->getName()) as $key => $value) { ?>
        <?= $key ?>: <?= $value ?>,
<?php } ?>
<?php $relation = $orm->getRelation($property->getName()); ?>
<?php if ($attributeType === 'OneToOne') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,

        cascade: [ 'persist', 'refresh', 'remove' ],
<?php } else if ($attributeType === 'OneToMany') { ?>
        mappedBy: '<?= $relation->getMappedBy() ?>',
        targetEntity: <?= $relation->getTargetEntity() ?>,
        cascade: [ 'persist', 'refresh', 'remove' ],
        orderBy: <?= $relation->getOrderByString() ?>,
        orphanRemoval: true,
<?php } else if ($attributeType === 'ManyToOne') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,
        inversedBy: '<?= $relation->getInversedBy() ?>',
        joinColumn: ['onDelete' => 'SET NULL'],
<?php } else if ($attributeType === 'ManyToMany') { ?>
        targetEntity: <?= $relation->getTargetEntity() ?>,
        orderBy: <?= $relation->getOrderByString() ?>,
        cascade: ['persist', 'refresh', 'remove'],
        joinTable: [
            'name' => '<?= $orm->getTableName() ?>_<?= $relation->getName() ?>',
            'joinColumns' => [
                'entity_id' => [
                    'referencedColumnName' => 'id',
                    'onDelete' => 'cascade',
                ],
            ],
            'inverseJoinColumns' => [
                'target_id' => [
                    'referencedColumnName' => 'id',
                    'onDelete' => 'cascade',
                ]
            ],
        ],
<?php } ?>
    )]
    private <?= $property->getNullable() .$property->getType() ; ?> $<?= $property->getName(); ?><?php if ($property->getDefault() !== 'null'): ?> = <?= $property->getDefault(); ?><?php endif; ?>;

<?php } ?>
<?php foreach ($class->getFunctions() as $function) { ?>
    <?= $function->getVisibility(); ?> function <?= $function->getName(); ?>(<?= $function->getArgumentString(); ?>)<?= $function->getReturnsString(); ?>

    {
        <?= $function->getBodyString(8); ?>

    }
<?php } ?>
}
