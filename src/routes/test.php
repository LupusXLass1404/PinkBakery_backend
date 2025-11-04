11

<?php
echo 'ffff';
$method = 'create';
$ctrl = new ApiController($args['table']);
$ctrl->$method($request, $response, $args);