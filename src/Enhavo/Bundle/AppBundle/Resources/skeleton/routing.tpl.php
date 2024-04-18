<?= $app; ?>_<?= $resource; ?>_index:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/index
    methods: [GET]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:indexAction
        _sylius:
            viewer:

<?= $app ?>_<?= $resource ?>_create:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/create
    methods: [GET,POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:createAction
        _sylius:
            redirect: <?= $app ?>_<?= $resource ?>_update
            viewer:

<?= $app ?>_<?= $resource ?>_update:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/update/{id}
    methods: [GET,POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:updateAction
        _sylius:
            viewer:

<?= $app ?>_<?= $resource ?>_table:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/table
    methods: [GET,POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:tableAction
        _sylius:
            viewer:
                columns:
                    id:
                        property: id
                        width: 12
                        label: id
                        type: property

<?= $app ?>_<?= $resource ?>_delete:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/delete/{id}
    methods: [POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:deleteAction

<?= $app ?>_<?= $resource ?>_batch:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/batch
    methods: [POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:batchAction
        _sylius:
            paginate: false
            criteria:
                id: $ids
            batches:
                delete:
                    type: delete

<?= $app ?>_<?= $resource ?>_preview:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/preview
    methods: [GET]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:previewAction
        _sylius:
            viewer:

<?= $app ?>_<?= $resource ?>_resource_preview:
    options:
        expose: true
    path: /<?= $app_url ?>/<?= $resource_url ?>/resource/preview
    methods: [POST]
    defaults:
        _controller: <?= $app ?>.controller.<?= $resource ?>:previewResourceAction
        _area: theme
        _sylius:
            viewer:
                strategy_options:
                    service: enhavo_article.controller.article:showResourceAction

