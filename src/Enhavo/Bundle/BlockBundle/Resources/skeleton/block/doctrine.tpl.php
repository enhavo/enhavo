<?= $class->getNamespace() ?>\<?= $class->getName() ?>:
    type: entity
    table: <?= $yaml->getTableName(); ?><?= "\n"; ?>

    fields:
<?php foreach($yaml->getFields() as $field): ?>
<?= $field ?>
<?php endforeach; ?>

    oneToOne:
<?php foreach($yaml->getRelations('oneToOne') as $field): ?>
<?= $field ?>
<?php endforeach; ?>

    oneToMany:
<?php foreach($yaml->getRelations('oneToMany') as $field): ?>
<?= $field ?>
<?php endforeach; ?>

    manyToOne:
<?php foreach($yaml->getRelations('manyToOne') as $field): ?>
<?= $field ?>
<?php endforeach; ?>

    manyToMany:
<?php foreach($yaml->getRelations('manyToMany') as $field): ?>
    <?= $field ?>
<?php endforeach; ?>

    lifecycleCallbacks: {  }
