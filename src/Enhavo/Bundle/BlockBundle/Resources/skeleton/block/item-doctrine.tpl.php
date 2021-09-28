<?= $class->getNamespace() ?>\<?= $class->getName() ?>:
    type: entity
    table: <?= $yaml->getTableName(); ?><?= "\n"; ?>

    id:
        id:
            type: integer
            id: true
            generator:
                strategy: AUTO

    fields:
<?php foreach($yaml->getFields() as $field): ?>
        <?= $field->getName() ?>:
            type: <?= $field->getType() ?>

            nullable: <?= $field->getNullableString() ?>

<?php endforeach; ?>

    oneToOne:
<?php foreach($yaml->getRelations('oneToOne') as $relation): ?>
        <?= $relation->getName() ?>:
            cascade: [ 'persist', 'refresh', 'remove' ]
            targetEntity: <?= $relation->getTargetEntity() ?>

<?php endforeach; ?>

    oneToMany:
<?php foreach($yaml->getRelations('oneToMany') as $relation): ?>
        <?= $relation->getName() ?>:
            targetEntity: <?= $relation->getTargetEntity() ?>

            mappedBy: <?= $relation->getMappedBy() ?>

            cascade: [ 'persist', 'refresh', 'remove' ]
            orderBy: <?= $relation->getOrderByString() ?>

            orphanRemoval: true

<?php endforeach; ?>

    manyToOne:
<?php foreach($yaml->getRelations('manyToOne') as $relation): ?>
        <?= $relation->getName() ?>:
            targetEntity: <?= $relation->getTargetEntity() ?>

            cascade: [ 'persist', 'refresh', 'remove' ]
            inversedBy: <?= $relation->getInversedBy() ?>

            joinColumn:
                onDelete: CASCADE
<?php endforeach; ?>

    manyToMany:
<?php foreach($yaml->getRelations('manyToMany') as $relation): ?>
        <?= $relation->getName() ?>:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: <?= $relation->getTargetEntity() ?>

            orderBy: <?= $relation->getOrderByString() ?>

            joinTable:
                name: <?= $yaml->getTableName() ?>_<?= $relation->getName() ?>

                joinColumns:
                    entity_id:
                        referencedColumnName: id
                        onDelete: cascade
                inverseJoinColumns:
                    target_id:
                        referencedColumnName: id
                        onDelete: cascade

<?php endforeach; ?>

    lifecycleCallbacks: {  }
