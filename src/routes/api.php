<?php
use Slim\App;
require_once __DIR__ . '/../controllers/ApiController.php';

// 工廠函式產生 route handler
function makeHandler($method) {
    return function ($request, $response, $args) use ($method) {
        $ctrl = new ApiController($args['table']);
        return $ctrl->$method($request, $response, $args);
    };
}

// group 只設定路徑前綴
return function (App $app) {
    $app->group('/api/{table}', function ($group) {
        $group->get('', makeHandler('getAll'));
        $group->get('/{id}', makeHandler('getOne'));
        $group->post('', makeHandler('create'));
        $group->put('/{id}', makeHandler('update'));
        $group->delete('/{id}', makeHandler('delete'));
    });
};