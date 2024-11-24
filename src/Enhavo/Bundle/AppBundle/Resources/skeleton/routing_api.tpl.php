<?= $route_api_prefix ?>_index:
    path: <?= $path_prefix ?>/index
    methods: [GET]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_index
            grid: <?= $grid_name."\n" ?>

<?= $route_api_prefix ?>_list:
    path: <?= $path_prefix ?>/list
    methods: [GET,POST]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_list
            grid: <?= $grid_name."\n" ?>

<?= $route_api_prefix ?>_batch:
    path: /article/batch
    methods: [POST]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_batch
            grid: <?= $grid_name."\n" ?>

<?= $route_api_prefix ?>_create:
    path: <?= $path_prefix ?>/create
    methods: [GET, POST]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_create
            input: <?= $input_name."\n" ?>

<?= $route_api_prefix ?>_update:
    path: <?= $path_prefix ?>/{id}
    methods: [GET, POST]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_update
            input: <?= $input_name."\n" ?>

<?= $route_api_prefix ?>_delete:
    path: <?= $path_prefix ?>/delete/{id}
    methods: [DELETE, POST]
    defaults:
        _expose: <?= $area ?>_api
        _endpoint:
            type: resource_delete
            input: <?= $input_name."\n" ?>
