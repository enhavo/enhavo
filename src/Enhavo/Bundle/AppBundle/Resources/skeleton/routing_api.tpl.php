<?php echo $route_api_prefix; ?>_index:
    path: <?php echo $path_prefix; ?>/index
    methods: [GET]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_index
            grid: <?php echo $grid_name."\n"; ?>

<?php echo $route_api_prefix; ?>_list:
    path: <?php echo $path_prefix; ?>/list
    methods: [GET,POST]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_list
            grid: <?php echo $grid_name."\n"; ?>

<?php echo $route_api_prefix; ?>_batch:
    path: /article/batch
    methods: [POST]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_batch
            grid: <?php echo $grid_name."\n"; ?>

<?php echo $route_api_prefix; ?>_create:
    path: <?php echo $path_prefix; ?>/create
    methods: [GET, POST]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_create
            input: <?php echo $input_name."\n"; ?>

<?php echo $route_api_prefix; ?>_update:
    path: <?php echo $path_prefix; ?>/{id}
    methods: [GET, POST]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_update
            input: <?php echo $input_name."\n"; ?>

<?php echo $route_api_prefix; ?>_delete:
    path: <?php echo $path_prefix; ?>/delete/{id}
    methods: [DELETE, POST]
    defaults:
        _expose: <?php echo $area; ?>_api
        _endpoint:
            type: resource_delete
            input: <?php echo $input_name."\n"; ?>
