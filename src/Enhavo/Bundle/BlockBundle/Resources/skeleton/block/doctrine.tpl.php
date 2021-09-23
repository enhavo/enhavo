<?= $yaml->getNamespace() ?>\<?= $yaml->getName() ?>:
    type: entity
    table: <?= $yaml->getTableName(); ?><?= "\n"; ?>

    fields:
<?php foreach($yaml->getFields() as $field): ?>
<?= $field ?>
<?php endforeach; ?>


    lifecycleCallbacks: {  }
