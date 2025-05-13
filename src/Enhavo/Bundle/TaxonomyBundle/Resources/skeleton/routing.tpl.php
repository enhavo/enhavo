<?php echo $bundle_name; ?>_<?php echo $name; ?>_index:
    options:
        expose: true
    path: /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/index
    methods: [GET]
    defaults:
        _controller: enhavo_taxonomy.controller.term:<?php if ($hierarchy) { ?>list<?php } else { ?>index<?php } ?>Action
        _sylius:
            viewer:
                title: taxonomy.label.term
                translationDomain: EnhavoTaxonomyBundle
                <?php if ($hierarchy) { ?>data<?php } else { ?>table<?php } ?>_route: <?php echo $bundle_name; ?>_<?php echo $name; ?>_<?php if ($hierarchy) { ?>data<?php } else { ?>table<?php } ?>
                batch_route: <?php echo $bundle_name; ?>_<?php echo $name; ?>_batch
                open_route: <?php echo $bundle_name; ?>_<?php echo $name; ?>_update
                actions:
                    create:
                        type: create
                        route: <?php echo $bundle_name; ?>_<?php echo $name; ?>_create

<?php echo $bundle_name; ?>_<?php echo $name; ?>_create:
    options:
        expose: true
    path:  /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/create
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:createAction
        _sylius:
            redirect: <?php echo $bundle_name; ?>_<?php echo $name; ?>_update
            form: '<?php echo $form_type; ?>'
            factory:
                method: createNewOnTaxonomy
                arguments:
                    - '<?php echo $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle

<?php echo $bundle_name; ?>_<?php echo $name; ?>_update:
    options:
        expose: true
    path:  /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/update/{id}
    methods: [GET,POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:updateAction
        _sylius:
            form: '<?php echo $form_type; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                actions_secondary:
                    delete:
                        type: delete
                        route: <?php echo $bundle_name; ?>_<?php echo $name; ?>_delete

<?php if (!$hierarchy) { ?>
<?php echo $bundle_name; ?>_<?php echo $name; ?>_table:
    options:
        expose: true
    path:  /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/table
    defaults:
        _controller: enhavo_taxonomy.controller.term:tableAction
        _sylius:
            repository:
                method: findByTaxonomyName
                arguments:
                    - '<?php echo $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                width: 12
                columns:
                    name:
                        type: text
                        width: 12
                        label: term.label.name
                        translation_domain: EnhavoTaxonomyBundle
                        property: name
<?php } else { ?>
<?php echo $bundle_name; ?>_<?php echo $name; ?>_data:
    options:
        expose: true
    path:  /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/data
    defaults:
        _controller: enhavo_taxonomy.controller.term:listDataAction
        _sylius:
            repository:
                method: findRootsByTaxonomyName
                arguments:
                    - '<?php echo $name; ?>'
            viewer:
                translationDomain: EnhavoTaxonomyBundle
                width: 12
                children_property: children
                parent_property: parent
                columns:
                    name:
                        type: text
                        width: 12
                        label: term.label.name
                        translation_domain: EnhavoTaxonomyBundle
                        property: name
<?php } ?>

<?php echo $bundle_name; ?>_<?php echo $name; ?>_delete:
    options:
        expose: true
    path: /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/delete/{id}
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:deleteAction

<?php echo $bundle_name; ?>_<?php echo $name; ?>_batch:
    options:
        expose: true
    path: /<?php echo $bundle_url; ?>/<?php echo $name_url; ?>/batch
    methods: [POST]
    defaults:
        _controller: enhavo_taxonomy.controller.term:batchAction
        _sylius:
            paginate: false
            criteria:
                id: $ids
            batches:
                delete:
                    type: delete
