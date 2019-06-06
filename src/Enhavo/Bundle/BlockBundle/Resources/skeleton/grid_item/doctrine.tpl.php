<?= $bundle_namespace; ?>\Entity\<?=  $item_sub_directory ? $item_sub_directory.'\\' : ''; ?><?= $item_name; ?>:
    type: entity
    table: <?= $table_name; ?><?= "\n"; ?>

    lifecycleCallbacks: {  }
